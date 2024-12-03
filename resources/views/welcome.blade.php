<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arkan Economic and Engineering Consulting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jobs.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Arkan Consulting</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jobs">Jobs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#statistics">Statistics</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title">Excellence in Economic & Engineering Consulting</h1>
                    <p class="hero-subtitle">Join our team of experts and shape the future of consulting</p>
                    <a href="#jobs" class="btn btn-light btn-lg mt-3">
                        <i class="fas fa-search me-2"></i>Explore Opportunities
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Company Overview -->
    @include('components.about-company')

    <!-- Statistics -->


    <!-- Jobs Section -->
    <section id="jobs" class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title">Current Opportunities</h2>
                    <p class="text-muted">Join our team of experts and contribute to groundbreaking projects</p>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="row mb-4" >
                <div class="col-md-8 mx-auto">
                    <form action="/" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search"
                                   placeholder="Search for positions..."
                                   value="{{ request('search') }}">
                            <select class="form-select" name="category" style="max-width: 200px;">
                                <option value="">All Categories</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Economics">Economics</option>
                                <option value="Consulting">Consulting</option>
                                <option value="Management">Management</option>
                            </select>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Job Listings -->
            <div class="row">
    @if(isset($jobs) && $jobs->count() > 0)
        @foreach($jobs as $job)
            <div class="col-lg-6 mb-4">
                @include('components.job-listing', ['job' => $job])
            </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                No positions available at the moment. Please check back later.
            </div>
        </div>
    @endif
</div>

<!-- Pagination -->
@if(isset($jobs) && $jobs instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="d-flex justify-content-center mt-4">
        {{ $jobs->links() }}
    </div>
@endif

        </div>
    </section>
    @include('components.statistics')
    <!-- Footer -->
    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>
</html>
