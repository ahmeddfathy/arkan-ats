@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm fade-in">
                <div class="card-header">
                    <h3 class="mb-0">Application Details</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-2">Job Position</h5>
                            <p class="h6">{{ $application->job->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted mb-2">Candidate</h5>
                            <p class="h6">{{ $application->candidate->user->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-2">Status</h5>
                            <span class="status-badge status-{{ strtolower($application->status) }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted mb-2">Application Date</h5>
                            <p class="h6">{{ $application->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-muted mb-2">Notes</h5>
                        <div class="p-3 bg-light rounded">
                            {{ $application->notes ?? 'No notes available' }}
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('applications.index') }}"
                            class="btn btn-secondary me-md-2">Back to List</a>
                        <a href="{{ route('applications.edit', $application->id) }}"
                            class="btn btn-primary">Edit Application</a>
                        @else
                        <a href="{{ route('user.dashboard') }}"
                            class="btn btn-secondary me-md-2">Back to List</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
