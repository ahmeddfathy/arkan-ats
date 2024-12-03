<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-page">
        <div class="auth-container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="auth-card">
                        <h1 class="text-center mb-4 fw-bold">Create Account</h1>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="John Doe"
                                       required
                                       autofocus>
                                <label for="name">Full Name</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="name@example.com"
                                       required>
                                <label for="email">Email address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select @error('role') is-invalid @enderror"
                                        id="role"
                                        name="role"
                                        required>
                                    <option value="" selected disabled>Choose Your Role</option>
                                    <option value="applicant" {{ old('role') == 'user' ? 'user' : '' }}>User</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'admin' : '' }}>HR</option>

                                </select>
                                <label for="role">Role</label>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
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

                            <div class="form-floating mb-4">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Confirm Password"
                                       required>
                                <label for="password_confirmation">Confirm Password</label>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="auth-link">Terms of Service</a> and <a href="#" class="auth-link">Privacy Policy</a>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-auth w-100 mb-4">
                                Create Account
                            </button>

                            <p class="text-center mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="auth-link">Sign in</a>
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
