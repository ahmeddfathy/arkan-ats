<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class JobController extends Controller
{

    public function index()
    {
        $jobs = Job::with('creator')->get();
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function getJobsByCategory($category)
    {
        try {
            $jobs = Job::where('category', $category)
                       ->where('status', 'active') // عرض الوظائف النشطة فقط
                       ->select('id', 'title')
                       ->get();

            return response()->json([
                'success' => true,
                'jobs' => $jobs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch jobs. Please try again.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'category' => 'required|in:IT,Graphic Design,Marketing,Social Media,Content Writing,Customer Service,Data Entry,Digital Marketing,Accounting,Sales,Engineering,HR,Software Development,Technical Support',
            'status' => 'required|in:active,inactive,closed',
        ]);

        Job::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'status' => $validated['status'],
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job created successfully');
    }

    public function show($id)
{

    $job = Job::with('creator')->findOrFail($id);

    return view('jobs.show', compact('job'));
}

    public function edit($id)
    {
        $job = Job::findOrFail($id);
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'requirements' => 'sometimes|string',
            'category' => 'required|in:IT,Graphic Design,Marketing,Social Media,Content Writing,Customer Service,Data Entry,Digital Marketing,Accounting,Sales,Engineering,HR,Software Development,Technical Support',
            'status' => 'sometimes|in:active,inactive,closed',
        ]);

        $job = Job::findOrFail($id);
        $job->update($validated);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully');
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully');
    }
}
