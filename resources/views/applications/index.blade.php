@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Applications List</h2>
        <a href="{{ route('applications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create New Application
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show slide-up" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Job Title</th>
                        <th>Candidate</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr class="fade-in">
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->job->title }}</td>
                        <td>{{ $application->candidate->user->name }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($application->status) }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($application->notes ?? 'No notes provided', 50) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('applications.show', $application->id) }}" 
                                   class="btn btn-info btn-sm">
                                    View
                                </a>
                                <a href="{{ route('applications.edit', $application->id) }}" 
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('applications.destroy', $application->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">No applications found</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection