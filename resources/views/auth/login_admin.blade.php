<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Login - SIAKAD PMB</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('template/kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('template/kaiadmin/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular", 
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('template/kaiadmin/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('template/kaiadmin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/kaiadmin/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/kaiadmin/css/kaiadmin.min.css') }}" />
    
    <style>
        body {
            background: linear-gradient(135deg, #1f1c2c 0%, #928dab 100%);
            min-height: 100vh;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .admin-brand {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .form-floating > label {
            color: #6c757d;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="card login-card">
                        <div class="row g-0">
                            <!-- Brand Section -->
                            <div class="col-lg-5">
                                <div class="admin-brand p-5 d-flex flex-column justify-content-center h-100 text-center">
                                    <div class="mb-4">
                                        <i class="fas fa-shield-alt fa-4x mb-3 opacity-75"></i>
                                        <h2 class="fw-bold">Admin Panel</h2>
                                        <p class="opacity-75 mb-0">Sistem Manajemen PMB</p>
                                    </div>
                                    <div class="mt-auto">
                                        <p class="small opacity-75 mb-0">
                                            <i class="fas fa-lock me-1"></i>
                                            Area terbatas untuk staff dan administrator
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Login Form -->
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <a href="{{ route('login') }}" class="btn btn-light btn-sm me-3">
                                                <i class="fas fa-arrow-left"></i>
                                            </a>
                                            <div>
                                                <h3 class="fw-bold mb-0">Login Administrator</h3>
                                                <p class="text-muted small mb-0">Masuk ke panel administrasi</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Login Gagal!</strong> {{ $errors->first() }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Login Gagal!</strong> {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('admin.login') }}">
                                        @csrf
                                        
                                        <div class="form-floating mb-3">
                                            <input type="email" 
                                                   name="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email"
                                                   placeholder="name@example.com"
                                                   value="{{ old('email') }}"
                                                   required>
                                            <label for="email">
                                                <i class="fas fa-envelope me-1"></i>
                                                Email Address
                                            </label>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input type="password" 
                                                   name="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   id="password"
                                                   placeholder="Password"
                                                   required>
                                            <label for="password">
                                                <i class="fas fa-lock me-1"></i>
                                                Password
                                            </label>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label text-muted small" for="remember">
                                                Ingat saya
                                            </label>
                                        </div>

                                        <div class="d-grid mb-3">
                                            <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                                <span id="loginText">
                                                    <i class="fas fa-sign-in-alt me-2"></i>
                                                    Masuk ke Admin Panel
                                                </span>
                                                <span id="loginLoading" style="display: none;">
                                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                    Sedang masuk...
                                                </span>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="text-center">
                                        <a href="#" class="text-decoration-none small text-muted">
                                            <i class="fas fa-question-circle me-1"></i>
                                            Lupa password?
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('template/kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('template/kaiadmin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('template/kaiadmin/js/core/bootstrap.min.js') }}"></script>
    
    <script>
        // Auto-focus first input
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            if (emailInput) emailInput.focus();
        });

        // Handle login form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginLoading = document.getElementById('loginLoading');
            
            // Disable button and show loading
            loginBtn.disabled = true;
            loginText.style.display = 'none';
            loginLoading.style.display = 'inline';
            
            // Prevent double submission
            setTimeout(() => {
                loginBtn.disabled = true;
            }, 100);
        });

        // Re-enable button if there are validation errors
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const loginBtn = document.getElementById('loginBtn');
                const loginText = document.getElementById('loginText');
                const loginLoading = document.getElementById('loginLoading');
                
                loginBtn.disabled = false;
                loginText.style.display = 'inline';
                loginLoading.style.display = 'none';
            });
        @endif
    </script>
</body>
</html>
