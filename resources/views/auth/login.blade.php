<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-page login-page">
        <div class="auth-container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="auth-card">
                        <h1 class="text-center mb-4 fw-bold">Welcome Back</h1>

                        @if ($errors->any())
    <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>


    <script>
        setTimeout(() => {
            const errorAlert = document.getElementById('error-alert');
            if (errorAlert) {

                errorAlert.classList.remove('show');
                errorAlert.classList.add('fade');

                setTimeout(() => errorAlert.remove(), 500);
            }
        }, 10000);
    </script>
@endif  

                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="form-floating mb-3">

                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="name@example.com"
                                       required
                                       autofocus>
                                <label for="email">Email address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-4">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Password"
                                       required>
                                <label for="password">Password</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-auth w-100 mb-4">
                                Sign In
                            </button>

                            <p class="text-center mb-0">
                                Don't have an account?
                                <a href="{{ route('register') }}" class="auth-link">Create one</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
