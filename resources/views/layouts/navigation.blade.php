<nav class="navbar navbar-expand-lg navbar-dark bg-primary " >
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-briefcase me-2"></i>ATS System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard link for both admin and user -->


                <!-- Candidates link (admin only) -->
                @if(Auth::check() && Auth::user()->hasRole('admin'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-line me-1"></i>Admin Dashboard
                    </a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('candidates.*') ? 'active' : '' }}" href="{{ route('candidates.index') }}">
                            <i class="fas fa-users me-1"></i>Candidates
                        </a>
                    </li>

                <!-- Jobs link for both admin and user -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
                        <i class="fas fa-briefcase me-1"></i>Jobs
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}" href="{{ route('applications.index') }}">
                        <i class="fas fa-briefcase me-1"></i>applications
                    </a>
                </li>

                @endif
                <!-- User-specific link (user only) -->
                @if(Auth::check() && Auth::user()->hasRole('user'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-user me-1"></i>User Dashboard
                        </a>
                    </li>

                    <li class="nav-item">

        <a class="nav-link {{ request()->routeIs('candidates.create') ? 'active' : '' }}" href="{{ route('candidates.create') }}">
            <i class="fas fa-user me-1"></i>Apply Job
        </a>
    </li>

                @endif
            </ul>

            <ul class="navbar-nav">
                <!-- User dropdown -->
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                    <li>
        <a href="/user/profile" class="dropdown-item">
            <i class="fas fa-user me-2"></i>Profile
        </a>
    </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
