@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 fade-in">
        <div class="col-md-8">
            <h2 class="fw-bold">Candidate Management</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('candidates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Add New Candidate
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show slide-up" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card fade-in">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Job</th>
                            <th>Experience</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidates as $candidate)
                            <tr class="align-middle">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            {{ substr($candidate->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $candidate->user->name }}</div>
                                            <div class="text-muted small">{{ $candidate->phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $candidate->job->title ?? 'N/A' }}</td>
                                <td>{{ $candidate->experience }} years</td>
                                <td>
                                    <span class="badge bg-info">{{ $candidate->category }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($candidate->status) }}">
                                        {{ ucfirst($candidate->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('candidates.show', $candidate->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                        <a href="{{ route('candidates.edit', $candidate->id) }}"
                                           class="btn btn-sm btn-outline-warning">
                                            Edit
                                        </a>
                                        <form action="{{ route('candidates.destroy', $candidate->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
