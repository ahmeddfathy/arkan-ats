@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('candidates/create') ? 'active' : '' }}" href="{{ route('candidates.create') }}">
                            <i class="bi bi-person-plus me-2"></i>
                            Apply Jobs
                        </a>
                    </li>
                
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Welcome Section -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Welcome, {{ $user->name }}!</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    @if(!$candidate)
                        <a href="{{ route('candidates.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-person-plus"></i> Create Profile
                        </a>
                    @endif
                </div>
            </div>

            <!-- Profile Status Alert -->
            @if(!$candidate)
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    You haven't created your candidate profile yet. Create your profile to start applying for jobs!
                </div>
            @endif

            <!-- Application Statistics -->
            @if($candidate)
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Applications</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applicationStats['total'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-file-text fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applicationStats['pending'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-hourglass-split fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Shortlisted</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applicationStats['shortlisted'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card border-left-danger shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                            Rejected</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applicationStats['rejected'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-x-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Applications -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Applications</h6>

                    </div>
                    <div class="card-body">
                        @if($applications->isEmpty())
                            <p class="text-center text-muted">No applications yet</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Status</th>
                                            <th>Applied Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applications as $application)
                                            <tr>
                                                <td>{{ $application->job->title }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'shortlisted' ? 'success' : 'danger') }}">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('applications.show', $application->id) }}" class="btn btn-sm btn-info">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Recent Jobs -->
            @if($candidate)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Job Openings</h6>
                    </div>
                    <div class="card-body">
                        @if($recentJobs->isEmpty())
                            <p class="text-center text-muted">No active jobs available</p>
                        @else
                            <div class="row">
                                @foreach($recentJobs as $job)
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $job->title }}</h5>
                                                <p class="card-text">{{ Str::limit($job->description, 100) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-info">{{ $job->category }}</span>
                                                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-sm btn-outline-primary">
                                                        View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
