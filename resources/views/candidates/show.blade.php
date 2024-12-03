@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center fade-in">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $candidate->user->name }}'s Profile</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="profile-info-label">Job Title</div>
                            <div class="profile-info-value">{{ $candidate->job->title ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-info-label">Experience</div>
                            <div class="profile-info-value">{{ $candidate->experience }} years</div>
                        </div>
                        <div class="col-md-4">
                            <div class="profile-info-label">Category</div>
                            <div class="profile-info-value">
                                <span class="badge bg-info">{{ $candidate->category }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="text-primary">Skills</h5>
                        <div class="skills-container">
                            @foreach(explode(',', $candidate->skills) as $skill)
                                <span class="badge bg-secondary me-2 mb-2">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="profile-info-label">Phone</div>
                            <div class="profile-info-value">{{ $candidate->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-label">Date of Birth</div>
                            <div class="profile-info-value">
                                {{ $candidate->date_of_birth ? \Carbon\Carbon::parse($candidate->date_of_birth)->format('d/m/Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="cv-section mb-4">
                        <h5 class="text-primary">CV Document</h5>
                        @if($candidate->cv)
                            <a href="{{ Storage::url($candidate->cv) }}"
                               class="btn btn-outline-primary"
                               target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>Download CV
                            </a>
                        @else
                            <p class="text-muted">No CV uploaded</p>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('candidates.edit', $candidate->id) }}"
                           class="btn btn-warning me-2">Edit Profile</a>
                        <form action="{{ route('candidates.destroy', $candidate->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this candidate?')">
                                Delete Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
