<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\Candidate;
use App\Services\CandidateProcessingService;
use App\Services\ApplicationStoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    private $candidateProcessingService;
    private $applicationStoreService;

    public function __construct(
        CandidateProcessingService $candidateProcessingService,
        ApplicationStoreService $applicationStoreService
    ) {
        $this->candidateProcessingService = $candidateProcessingService;
        $this->applicationStoreService = $applicationStoreService;
    }

    public function getCandidatesByJob($jobId)
    {
        try {
            $job = Job::findOrFail($jobId);
            $candidates = Candidate::with('user')
                ->where('job_id', $jobId)
                ->orWhere(function($query) use ($job) {
                    $query->whereNull('job_id')
                        ->where('category', $job->category);
                })
                ->get();

            return response()->json($candidates);
        } catch (\Exception $e) {
            Log::error('Error fetching candidates by job: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch candidates'], 500);
        }
    }

    public function sendToGoogleAI()
    {
        try {
            $candidates = Candidate::where('is_process', 0)->get();

            if ($candidates->isEmpty()) {
                return response()->json(['message' => 'No unprocessed candidates found'], 404);
            }

            $results = $this->candidateProcessingService->processUnprocessedCandidates();

            return response()->json([
                'message' => 'Candidates processed successfully',
                'results' => $results
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in sendToGoogleAI: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to process candidates',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $applications = Application::with(['job', 'candidate.user'])->get();
            return view('applications.index', compact('applications'));
        } catch (\Exception $e) {
            Log::error('Error fetching applications: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch applications');
        }
    }

    public function create()
    {
        try {
            $jobs = Job::all();
            $candidates = Candidate::with('user')->get();
            return view('applications.create', compact('jobs', 'candidates'));
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load application form');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'candidate_id' => 'required|exists:candidates,id',
            'status' => 'required|in:pending,shortlisted,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Check if the application already exists
            $existingApplication = Application::where('job_id', $validated['job_id'])
                                              ->where('candidate_id', $validated['candidate_id'])
                                              ->first();

            if ($existingApplication) {
                return redirect()->back()
                    ->with('error', 'This application has already been created.');
            }

            // Create the application if it doesn't exist
            Application::create($validated);

            return redirect()->route('applications.index')
                ->with('success', 'Application created successfully');
        } catch (\Exception $e) {
            Log::error('Application creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create application')
                ->withInput();
        }
    }


    public function edit($id)
    {
        try {
            $application = Application::findOrFail($id);
            $jobs = Job::all();
            $candidates = Candidate::with('user')->get();

            return view('applications.edit', compact('application', 'jobs', 'candidates'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load application form');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'candidate_id' => 'required|exists:candidates,id',
            'status' => 'required|in:pending,shortlisted,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($id, $validated) {
                $application = Application::findOrFail($id);
                $application->update($validated);
            });

            return redirect()->route('applications.index')
                ->with('success', 'Application updated successfully');
        } catch (\Exception $e) {
            Log::error('Application update failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update application')
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $application = Application::with(['job', 'candidate.user'])->findOrFail($id);
            return view('applications.show', compact('application'));
        } catch (\Exception $e) {
            Log::error('Error showing application: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load application details');
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $application = Application::findOrFail($id);
                $application->delete();
            });

            return redirect()->route('applications.index')
                ->with('success', 'Application deleted successfully');
        } catch (\Exception $e) {
            Log::error('Application deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete application');
        }
    }
}
