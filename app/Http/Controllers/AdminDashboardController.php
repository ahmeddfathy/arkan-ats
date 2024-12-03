<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Application;
use App\Models\Candidate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AdminDashboardController extends Controller
{
    //
    public function index()
    {
        // Fetch dashboard statistics
        $statistics = $this->getDashboardStatistics();

        // Fetch recent activities
        $recentActivities = $this->getRecentActivities();

        // Fetch job statistics
        $jobStats = $this->getJobStatistics();

        return view('dashboard', compact('statistics', 'recentActivities', 'jobStats'));
    }

    private function getDashboardStatistics()
    {
        return [
            'total_jobs' => Job::count(),
            'total_applications' => Application::count(),
            'total_candidates' => Candidate::count(),
            'active_jobs' => Job::where('status', 'active')->count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'shortlisted_candidates' => Application::where('status', 'shortlisted')->count(),
        ];
    }

    private function getRecentActivities()
    {
        return DB::table('applications')
            ->join('candidates', 'applications.candidate_id', '=', 'candidates.id')
            ->join('jobs', 'applications.job_id', '=', 'jobs.id')
            ->join('users', 'candidates.user_id', '=', 'users.id')
            ->select('applications.*', 'jobs.title as job_title',
                    'users.name as candidate_name')
            ->orderBy('applications.created_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getJobStatistics()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        return [
            'monthly_applications' => Application::where('created_at', '>=', $thirtyDaysAgo)
                ->count(),
            'category_distribution' => Job::select('category', DB::raw('count(*) as total'))
                ->groupBy('category')
                ->get(),
            'status_distribution' => Application::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
        ];
    }
}
