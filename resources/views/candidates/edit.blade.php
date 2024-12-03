@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center fade-in">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Candidate Profile</h4>
                    <x-status-badge :status="$candidate->is_process === 1 ? 'shortlisted' : ($candidate->is_process === 2 ? 'rejected' : 'pending')" />
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('candidates.update', $candidate->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="needs-validation"
                        novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Category Select --}}
                        <div class="mb-4">
                            <label for="category" class="form-label">Category</label>
                            <select id="category"
                                name="category"
                                class="form-select @error('category') is-invalid @enderror"
                                required>
                                <option value="">Select Category</option>
                                @foreach(['IT', 'Graphic Design', 'Marketing', 'Software Development', 'HR'] as $category)
                                <option value="{{ $category }}" {{ old('category', $candidate->category) == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                                @endforeach
                            </select>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Position Select --}}
                        <div class="mb-4">
                            <label for="job_id" class="form-label">Job Position</label>
                            <select id="job_id"
                                name="job_id"
                                class="form-select @error('job_id') is-invalid @enderror"
                                required>
                                <option value="">Select Job</option>
                                @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ old('job_id', $candidate->job_id) == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }}
                                </option>
                                @endforeach
                            </select>
                            @error('job_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Experience and Phone --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="experience" class="form-label">Experience (years)</label>
                                <input type="number"
                                    name="experience"
                                    value="{{ old('experience', $candidate->experience) }}"
                                    class="form-control @error('experience') is-invalid @enderror"
                                    required>
                                @error('experience')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel"
                                    name="phone"
                                    value="{{ old('phone', $candidate->phone) }}"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    maxlength="15"
                                    required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="mb-4">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth', $candidate->date_of_birth ? \Carbon\Carbon::parse($candidate->date_of_birth)->format('Y-m-d') : '') }}"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                required>
                            @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Skills Section --}}
                        <div class="mb-4">
                            <label for="skills" class="form-label">Skills</label>
                            <textarea name="skills"
                                class="form-control @error('skills') is-invalid @enderror"
                                rows="3"
                                required>{{ old('skills', $candidate->skills) }}</textarea>
                            <div class="form-text">Separate skills with commas</div>
                            @error('skills')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CV Upload Section --}}
                        <div class="mb-4">
                            <label for="cv" class="form-label">CV Document</label>
                            <input type="file"
                                name="cv"
                                class="form-control @error('cv') is-invalid @enderror"
                                accept=".pdf,.doc,.docx">
                            <div class="form-text">
                                Current CV:
                                @if($candidate->cv)
                                <a href="{{ Storage::url($candidate->cv) }}" target="_blank">
                                    {{ basename($candidate->cv) }}
                                </a>
                                @else
                                No file uploaded
                                @endif
                            </div>
                            @error('cv')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($candidate->ai_feedback)
                        <div class="mb-4">
                            <x-ai-feedback :feedback="$candidate->ai_feedback" />
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('candidates.show', $candidate->id) }}"
                                class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Profile
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Category change handler
    document.getElementById('category').addEventListener('change', function() {
        const category = this.value;
        const jobSelect = document.getElementById('job_id');

        // Clear current job options
        jobSelect.innerHTML = '<option value="">Select Job</option>';

        if (category) {
            fetch(`/jobs-by-category/${category}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.jobs.forEach(job => {
                            const option = document.createElement('option');
                            option.value = job.id;
                            option.textContent = job.title;
                            // Check if this was the previously selected job
                            if (job.id == "{{ old('job_id', $candidate->job_id) }}") {
                                option.selected = true;
                            }
                            jobSelect.appendChild(option);
                        });
                    } else {
                        console.error('No jobs found for the selected category.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });

    // Form validation
    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // Trigger category change if there's a previously selected value
    window.addEventListener('load', function() {
        const categorySelect = document.getElementById('category');
        if (categorySelect.value) {
            categorySelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
