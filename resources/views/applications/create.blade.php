@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm fade-in">
                <div class="card-header">
                    <h3 class="mb-0">Create New Application</h3>
                </div>
                <div class="card-body p-4">
                @if (session('error'))
    <div class="alert alert-danger slide-up">
        <ul class="mb-0">
            <li>{{ session('error') }}</li>
        </ul>
    </div>
@endif

                    <form action="{{ route('applications.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="job_id" class="form-label">Select Job</label>
                            <select name="job_id" id="job_id" class="form-select" required>
                                <option value="">Choose a job position...</option>
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="candidate_id" class="form-label">Select Candidate</label>
                            <select name="candidate_id" id="candidate_id" class="form-select" required>
                                <option value="">Choose a candidate...</option>
                                @foreach($candidates as $candidate)
                                    <option value="{{ $candidate->id }}">{{ $candidate->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status" class="form-label">Application Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="shortlisted">Shortlisted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes"
                                      id="notes"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Add any relevant notes about the application...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('applications.index') }}"
                               class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
