<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the candidate profile with related applications and jobs
        $candidate = Candidate::with(['applications.job', 'job']) // Added 'jobs' to eager load jobs
            ->where('user_id', $user->id)
            ->first();

        // If no candidate exists, return empty data
        if (!$candidate) {
            return view('dashboard-user', [
                'user' => $user,
                'candidate' => null,
                'applications' => collect(),
                'applicationStats' => [
                    'total' => 0,
                    'pending' => 0,
                    'shortlisted' => 0,
                    'rejected' => 0,
                ],
                'recentJobs' => collect(),
            ]);
        }

        // Get the 5 most recent applications with their related jobs
        $applications = Application::with('job')
            ->where('candidate_id', $candidate->id)->get();

        // Calculate application statistics
        $applicationStats = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
        ];

        // Get recent jobs through the applications relationship
        $recentJobs = $candidate->jobs() // This gets jobs via the pivot table
            ->orderBy('applications.created_at', 'desc')
            ->get();

        

        // Return the view with all the data
        return view('dashboard-user', compact(
            'user',
            'candidate',
            'applications',
            'applicationStats',
            'recentJobs'
        ));
    }
}
