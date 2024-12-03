@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card job-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ $job->title }}</h3>
                        <span class="status-badge status-{{ $job->status }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="text-primary">Category</h5>
                        <p class="mb-0">{{ $job->category }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-primary">Description</h5>
                        <p class="mb-0">{{ $job->description }}</p>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-primary">Requirements</h5>
                        <p class="mb-0">{{ $job->requirements }}</p>
                    </div>

                    <div class="d-flex justify-content-between mt-4">


                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Jobs
                        </a>
                        <div>
                            <a href="{{ route('jobs.edit', $job->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                            <form action="{{ route('jobs.destroy', $job->id) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this job?')">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </form>
                        </div>
                        @else    <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
    @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
