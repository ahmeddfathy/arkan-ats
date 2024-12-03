@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm fade-in">
                <div class="card-header">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Application
                    </h3>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                    <div class="alert alert-danger slide-up">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('applications.update', $application->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="job_id" class="form-label">
                                <i class="fas fa-briefcase me-1"></i>Job Position
                            </label>
                            <select name="job_id" id="job_id" class="form-select" required>
                                <option value="">Select Job Position</option>
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}"
                                            {{ $job->id == $application->job_id ? 'selected' : '' }}>
                                        {{ $job->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="candidate_id" class="form-label">
                                <i class="fas fa-user me-1"></i>Candidate
                            </label>
                            <select name="candidate_id" id="candidate_id" class="form-select" required>
                                <option value="">Select Candidate</option>
                                @foreach($candidates as $candidate)
                                    <option value="{{ $candidate->id }}"
                                            {{ $candidate->id == $application->candidate_id ? 'selected' : '' }}>
                                        {{ $candidate->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="loading-indicator" class="text-center d-none">
                                <div class="spinner-border text-primary mt-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-flag me-1"></i>Status
                            </label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>
                                    Shortlisted
                                </option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes
                            </label>
                            <textarea name="notes"
                                    id="notes"
                                    class="form-control"
                                    rows="4"
                                    placeholder="Add any relevant notes...">{{ $application->notes }}</textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('applications.index') }}"
                               class="btn btn-secondary me-md-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jobSelect = document.getElementById('job_id');
    const candidateSelect = document.getElementById('candidate_id');
    const loadingIndicator = document.getElementById('loading-indicator');

    // Store the initial candidate ID for restoration if needed
    const initialCandidateId = candidateSelect.value;

    jobSelect.addEventListener('change', function() {
        const jobId = this.value;

        if (!jobId) {
            return;
        }

        // Show loading indicator
        loadingIndicator.classList.remove('d-none');
        candidateSelect.disabled = true;

        // Fetch candidates for selected job
        fetch(`/applications/candidates-by-job/${jobId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(candidates => {
                // Clear current options except the first one
                candidateSelect.innerHTML = '<option value="">Select Candidate</option>';

                // Add new options
                candidates.forEach(candidate => {
                    const option = new Option(candidate.user.name, candidate.id);
                    option.selected = candidate.id == initialCandidateId;
                    candidateSelect.add(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to fetch candidates. Please try again.');
            })
            .finally(() => {
                // Hide loading indicator
                loadingIndicator.classList.add('d-none');
                candidateSelect.disabled = false;
            });
    });
});
</script>
@endpush
@endsection
