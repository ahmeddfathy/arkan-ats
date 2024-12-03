@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Job Positions</h2>
        <a href="{{ route('jobs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Position
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr class="fade-in">
                        <td>
                            <div class="job-position">
                                <div class="job-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                {{ $job->title }}
                            </div>
                        </td>
                        <td>
                            <span class="job-category">
                                <i class="fas fa-tag"></i>
                                {{ $job->category }}
                            </span>
                        </td>
                        <td>{{ Str::limit($job->description, 50) }}</td>
                        <td>
                            <span class="job-status status-{{ $job->status }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="job-actions">
                                <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-2x mb-3"></i>
                                <p>No job positions found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
