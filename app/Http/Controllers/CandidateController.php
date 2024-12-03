<?php
namespace App\Http\Controllers;

use App\Models\Candidate;
// use App\Jobs\CallRouteInBackground;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class CandidateController extends Controller
{
    public function index()
    {
        // Fetch candidates along with their associated user and job data
        $candidates = Candidate::with(['user', 'job'])->get(); // Added job relationship
        return view('candidates.index', compact('candidates'));
    }

    public function create()
    {
        $jobs = \App\Models\Job::select('id', 'title', 'category')
                               ->where('status', 'active')
                               ->get();

        return view('candidates.create', compact('jobs'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'experience' => 'required|integer|min:0|max:50',
            'skills' => 'required|string|max:1000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'category' => 'required|in:IT,Graphic Design,Marketing,Software Development,HR',
            'phone' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(22)->format('Y-m-d')
            ],
            'job_id' => [
                'required',
                'exists:jobs,id',
                function ($attribute, $value, $fail) use ($request) {
                    $job = Job::find($value);
                    if ($job && $job->category !== $request->category) {
                        $fail('The selected job must belong to the selected category.');
                    }
                },
            ],
        ], [
            'date_of_birth.before_or_equal' => 'You must be at least 22 years old.',
            'phone.regex' => 'Phone number must contain only digits.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.max' => 'Phone number cannot exceed 15 digits.',
        ]);

        try {
            $filePath = $request->file('cv')->store('cvs', 'public');

            $candidate = Candidate::create([
                'user_id' => auth()->id(),
                'cv' => $filePath,
                'experience' => $validated['experience'],
                'skills' => $validated['skills'],
                'category' => $validated['category'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'job_id' => $validated['job_id'],
            ]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Candidate profile created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create candidate: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to create candidate profile. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'experience' => 'sometimes|integer|min:0',
            'skills' => 'sometimes',
            'cv' => 'sometimes|file|mimes:pdf,doc,docx|max:2048', // Optional file upload
            'category' => 'sometimes|in:IT,Graphic Design,Marketing,Social Media,Content Writing,Customer Service,Data Entry,Digital Marketing,Accounting,Sales,Engineering,HR,Software Development,Technical Support',
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'job_id' => 'sometimes|exists:jobs,id', // Validate the job_id if provided
        ]);

        // Find the candidate by ID
        $candidate = Candidate::findOrFail($id);

        // If a new CV is uploaded, store it and update the candidate's cv field
        if ($request->hasFile('cv')) {
            // Delete the old CV if exists (Optional, if you want to delete the old file)
            if (file_exists(storage_path('app/public/' . $candidate->cv))) {
                unlink(storage_path('app/public/' . $candidate->cv));
            }

            // Store the new CV with its original name
            $filePath = $request->file('cv')->storeAs('cvs', $request->file('cv')->getClientOriginalName(), 'public');
            $validated['cv'] = $filePath;
        }

        // Update the candidate record with the validated data
        $candidate->update($validated);

        // Redirect to the candidates index page with a success message
        return redirect()->route('candidates.index')->with('success', 'Candidate updated successfully');
    }

    public function show($id)
{

    $candidate = Candidate::with(['job', 'user'])->findOrFail($id);


    return view('candidates.show', compact('candidate'));
}

    public function edit($id)
    {
        // Find the candidate by ID and return the edit view
        $candidate = Candidate::findOrFail($id);

        // Get all active jobs for the job dropdown
        $jobs = \App\Models\Job::select('id', 'title')->where('status', 'active')->get();


        return view('candidates.edit', compact('candidate', 'jobs'));
    }

    public function destroy($id)
    {
        // Find the candidate by ID and delete the record
        $candidate = Candidate::findOrFail($id);

        // Optionally delete the CV file if you want to remove it
        if (file_exists(storage_path('app/public/' . $candidate->cv))) {
            unlink(storage_path('app/public/' . $candidate->cv));
        }

        $candidate->delete();

        // Redirect to the candidates index page with a success message
        return redirect()->route('candidates.index')->with('success', 'Candidate deleted successfully');
    }
}
