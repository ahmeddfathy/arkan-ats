<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the candidate profile for the authenticated user, if it exists
        $candidate = Candidate::where('user_id', $user->id)->first();

        // Get the user's applications based on their candidate profile
        $applications = collect();
        if ($candidate) {
            // Fetch applications related to the candidate, limit to 5 most recent
            $applications = Application::with('job')
                ->where('candidate_id', $candidate->id)
                ->latest()
                ->take(5)
                ->get();
        }

        // Get application statistics for the user
        $applicationStats = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
        ];

        // Get the most recent job openings (optional, if you want to display them)
        $recentJobs = $candidate ? $candidate->jobs()->latest()->take(5)->get() : collect();

        // Return the view with the necessary data
        return view('dashboard-user', compact(
            'user',
            'candidate',
            'applications',
            'applicationStats',
            'recentJobs'
        ));
    }
}
