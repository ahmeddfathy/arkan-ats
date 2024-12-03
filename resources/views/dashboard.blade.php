
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
            <!-- Dashboard Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Admin Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="{{ route('jobs.create') }}" class="btn btn-sm btn-outline-primary">New Job</a>
                        <a href="{{ route('applications.index') }}" class="btn btn-sm btn-outline-secondary">View Applications</a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jobs</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_jobs'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-briefcase fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Jobs</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['active_jobs'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Applications</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_applications'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-file-text fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Applications</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['pending_applications'] }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-hourglass-split fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Candidate</th>
                                            <th>Job</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentActivities as $activity)
                                        <tr>
                                            <td>{{ $activity->candidate_name }}</td>
                                            <td>{{ $activity->job_title }}</td>
                                            <td>
                                                <span class="badge bg-{{ $activity->status === 'pending' ? 'warning' : ($activity->status === 'shortlisted' ? 'success' : 'danger') }}">
                                                    {{ ucfirst($activity->status) }}
                                                </span>
                                            </td>
                                            <td>{{ Carbon\Carbon::parse($activity->created_at)->format('M d, Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Statistics -->
            <div class="row mt-4">
                <div class="col-xl-6 col-lg-6">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-12 col-md-6 col-lg-8 ">
                        <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Applications by Category</h6>
                        </div>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-8">
                            <div class="card-body">
                            <div class="chart-pie pt-4">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                            </div>
                        </div>

                    </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-6 col-lg-6">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-sm-12 col-md-6 col-lg-8 ">
                        <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Application Status Distribution</h6>
                        </div>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-8">
                            <div class="card-body">
                            <div class="chart-pie pt-4">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize charts with the data from jobStats
    const categoryData = @json($jobStats['category_distribution']);
    const statusData = @json($jobStats['status_distribution']);

    // Category Chart
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: categoryData.map(item => item.category),
            datasets: [{
                data: categoryData.map(item => item.total),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                ]
            }]
        }
    });

    // Status Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.status),
            datasets: [{
                data: statusData.map(item => item.total),
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#e74a3b'
                ]
            }]
        }
    });
</script>
@endpush
@endsection
