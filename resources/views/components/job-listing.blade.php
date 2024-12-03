@props(['job'])

<div class="job-card">
    <div class="job-header d-flex justify-content-between align-items-start">
        <div>
            <h3 class="job-title">{{ $job->title }}</h3>
            <div class="company-name">
                <i class="fas fa-building me-2"></i>
                Arkan Economic and Engineering Consulting
            </div>
        </div>
        <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">
            {{ ucfirst($job->status) }}
        </span>
    </div>

    <div class="job-tags mt-3">
        <span class="job-tag">
            <i class="fas fa-briefcase me-1"></i>
            {{ $job->category }}
        </span>
        <span class="job-tag">
            <i class="fas fa-clock me-1"></i>
            Full Time
        </span>
    </div>

    <div class="job-description mt-3">
        {{ Str::limit($job->description, 150) }}
    </div>

    <div class="job-requirements mt-3">
        <strong><i class="fas fa-list-ul me-2"></i>Key Requirements:</strong>
        <p class="mt-2">{{ Str::limit($job->requirements, 100) }}</p>
    </div>

    <div class="job-meta mt-3">
        <span><i class="fas fa-calendar-alt me-1"></i>Posted {{ $job->created_at->diffForHumans() }}</span>
    </div>

    <div class="mt-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-primary">
            <i class="fas fa-eye me-1"></i> View Details
        </a>
        @auth


                <a  class="btn btn-outline-primary" href="{{route('candidates.create') }}">
                    <i class="fas fa-paper-plane me-1"></i> Quick Apply
</a>

        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                <i class="fas fa-sign-in-alt me-1"></i> Login to Apply
            </a>
        @endauth
    </div>
</div>
