<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Login - SIAKAD PMB</title>
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
        }
        .brand-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 0 0 15px;
        }
        .login-section {
            border-radius: 0 15px 15px 0;
        }
        .btn-google {
            background: #db4437;
            border-color: #db4437;
            color: white;
        }
        .btn-google:hover {
            background: #c23321;
            border-color: #c23321;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="card login-card">
                        <div class="row g-0">
                            <!-- Brand Section -->
                            <div class="col-lg-6">
                                <div class="brand-section p-5 d-flex flex-column justify-content-center h-100">
                                    <div class="text-center">
                                        <h1 class="fw-bold mb-3">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            SIAKAD PMB
                                        </h1>
                                        <p class="lead mb-4">Sistem Informasi Akademik<br>Penerimaan Mahasiswa Baru</p>
                                        <div class="mb-4">
                                            <i class="fas fa-university fa-3x opacity-75"></i>
                                        </div>
                                        <p class="small opacity-75">Bergabunglah dengan ribuan mahasiswa yang telah memilih masa depan terbaik bersama kami.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Login Section -->
                            <div class="col-lg-6">
                                <div class="login-section p-5">
                                    <div class="text-center mb-4">
                                        <h3 class="fw-bold text-dark">Selamat Datang!</h3>
                                        <p class="text-muted">Pilih metode login yang sesuai dengan peran Anda</p>
                                    </div>

                                    <div class="d-grid gap-3">
                                        <!-- Google Login for Students -->
                                        <a href="{{ route('google.redirect') }}" class="btn btn-google btn-lg">
                                            <i class="fab fa-google me-2"></i>
                                            Masuk dengan Google
                                            <small class="d-block">Untuk Calon Mahasiswa</small>
                                        </a>
                                        
                                        <!-- Admin Login -->
                                        <a href="{{ route('login.admin') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-user-shield me-2"></i>
                                            Login Staff / Admin
                                            <small class="d-block">Untuk Pengelola Sistem</small>
                                        </a>
                                    </div>

                                    <hr class="my-4">
                                    
                                    <div class="text-center">
                                        <p class="small text-muted mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Butuh bantuan? 
                                            <a href="#" class="text-decoration-none">Hubungi Support</a>
                                        </p>
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
</body>
</html>
