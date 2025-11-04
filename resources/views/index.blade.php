<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIAKAD PMB - Penerimaan Mahasiswa Baru</title>
    <link rel="icon" href="{{ asset('template/kaiadmin/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="{{ asset('template/kaiadmin/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons",
                ],
                urls: ["{{ asset('template/kaiadmin/css/fonts.min.css') }}"],
            },
            active: function () { sessionStorage.fonts = true; },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('template/kaiadmin/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/kaiadmin/css/kaiadmin.min.css') }}" />

    <style>
        :root {
            --primary-color: #6a11cb;
            --secondary-color: #2575fc;
            --text-light: #f8f9fa;
            --text-dark: #343a40;
            --bg-light: #ffffff;
            --bg-dark: #1a2035;
        }
        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }
        .navbar {
            transition: background-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .navbar-scrolled {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .navbar-scrolled .nav-link, .navbar-scrolled .navbar-brand {
            color: var(--text-dark) !important;
        }
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero-section .hero-shape {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }
        .hero-section .hero-shape svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }
        .hero-section .hero-shape .shape-fill {
            fill: var(--bg-light);
        }
        .section-title {
            font-weight: 700;
            color: var(--primary-color);
        }
        .feature-card, .program-card, .timeline-panel, .faq-item {
            background: var(--bg-light);
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.07);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover, .program-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 2.5rem;
            background: -webkit-linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .timeline {
            position: relative;
            padding: 0;
            list-style: none;
        }
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 40px;
            width: 3px;
            background: #e9ecef;
            border-radius: 3px;
        }
        .timeline > li {
            position: relative;
            margin-bottom: 50px;
            margin-left: 70px;
        }
        .timeline > li:before, .timeline > li:after {
            content: " ";
            display: table;
        }
        .timeline > li:after {
            clear: both;
        }
        .timeline-badge {
            position: absolute;
            top: 0;
            left: -30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 0 0 5px #fff;
        }
        .faq-item .btn-link {
            width: 100%;
            text-align: left;
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
        }
        .footer {
            background-color: var(--bg-dark);
            color: rgba(255,255,255,0.7);
        }
        .footer a {
            color: var(--text-light);
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .scroll-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-graduation-cap me-2"></i> SIAKAD PMB
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tahapan">Tahapan</a></li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-light btn-round" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i> Login / Daftar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-3 fw-bold mb-4">Mulai Perjalanan Akademikmu Bersama Kami</h1>
                    <p class="lead mb-4">Pendaftaran Mahasiswa Baru untuk <span id="typed-text"></span></p>
                    <a href="{{ route('login') }}" class="btn btn-warning btn-lg btn-round px-5 py-3">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-shape">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <!-- Timeline Section -->
    <section id="tahapan" class="py-5 bg-light">
        <div class="container py-5">
            <div class="row text-center mb-5 scroll-reveal">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-title">Tahapan Pendaftaran</h2>
                    <p class="text-muted">Ikuti 4 langkah mudah untuk menjadi bagian dari kami.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <ul class="timeline">
                        <li class="scroll-reveal">
                            <div class="timeline-badge"><i class="fas fa-user-plus"></i></div>
                            <div class="timeline-panel p-4">
                                <h4 class="fw-bold">1. Buat Akun & Isi Formulir</h4>
                                <p class="text-muted">Daftarkan diri Anda dengan membuat akun baru, lalu lengkapi formulir pendaftaran secara online dengan data yang valid.</p>
                            </div>
                        </li>
                        <li class="scroll-reveal">
                            <div class="timeline-badge"><i class="fas fa-file-upload"></i></div>
                            <div class="timeline-panel p-4">
                                <h4 class="fw-bold">2. Unggah Dokumen</h4>
                                <p class="text-muted">Unggah semua dokumen persyaratan yang diperlukan seperti ijazah, transkrip nilai, dan pas foto melalui portal pendaftaran.</p>
                            </div>
                        </li>
                        <li class="scroll-reveal">
                            <div class="timeline-badge"><i class="fas fa-tasks"></i></div>
                            <div class="timeline-panel p-4">
                                <h4 class="fw-bold">3. Seleksi & Ujian</h4>
                                <p class="text-muted">Ikuti proses seleksi sesuai jalur yang dipilih. Jadwal dan teknis ujian akan diinformasikan melalui akun Anda.</p>
                            </div>
                        </li>
                        <li class="scroll-reveal">
                            <div class="timeline-badge"><i class="fas fa-check-circle"></i></div>
                            <div class="timeline-panel p-4">
                                <h4 class="fw-bold">4. Pengumuman & Registrasi Ulang</h4>
                                <p class="text-muted">Hasil seleksi akan diumumkan secara online. Calon mahasiswa yang lulus diharapkan melakukan registrasi ulang sesuai jadwal.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Footer -->
    <footer class="footer pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-white fw-bold">SIAKAD PMB</h5>
                    <p>Sistem Informasi Penerimaan Mahasiswa Baru yang terintegrasi dan modern.</p>
                    <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5 class="text-white fw-bold">Navigasi</h5>
                    <ul class="list-unstyled">
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#tahapan">Tahapan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5 class="text-white fw-bold">Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> (021) 123-4567</li>
                        <li><i class="fas fa-envelope me-2"></i> pmb@university.ac.id</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Pendidikan No. 123</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="text-white fw-bold">Ikuti Kami</h5>
                    <div>
                        <a href="#" class="fs-4 me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="fs-4 me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="fs-4 me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="fs-4"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
            <div class="text-center">
                <p class="mb-0 small">© {{ date('Y') }} SIAKAD PMB. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Core JS Files -->
    <script src="{{ asset('template/kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('template/kaiadmin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('template/kaiadmin/js/core/bootstrap.min.js') }}"></script>
    
    <!-- Typed.js for animated text -->
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Typed.js animation
            new Typed('#typed-text', {
                strings: ["Tahun Ajaran 2025/2026", "Jalur Prestasi", "Jalur Reguler", "Jalur Undangan"],
                typeSpeed: 70,
                backSpeed: 50,
                backDelay: 2000,
                loop: true
            });

            // Navbar scroll effect
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            // Scroll reveal animations
            const revealElements = document.querySelectorAll('.scroll-reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            revealElements.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
