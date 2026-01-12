<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard.index') }}" class="logo">
                <img src="{{ asset('template/kaiadmin/img/kaiadmin/logo_light.svg') }}" alt="SIAKAD Logo" class="navbar-brand" height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <!-- Search Form -->
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pe-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                    </div>
                    <input type="text" placeholder="Cari data PMB..." class="form-control" />
                </div>
            </nav>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <!-- Mobile Search -->
                <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
                        <i class="fa fa-search"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-search animated fadeIn">
                        <form class="navbar-left navbar-form nav-search">
                            <div class="input-group">
                                <input type="text" placeholder="Cari..." class="form-control" />
                            </div>
                        </form>
                    </ul>
                </li>

                <!-- Messages -->
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-envelope"></i>
                    </a>
                    <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
                        <li>
                            <div class="dropdown-title d-flex justify-content-between align-items-center">
                                Pesan
                                <a href="#" class="small">Tandai semua dibaca</a>
                            </div>
                        </li>
                        <li>
                            <div class="message-notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    <a href="#">
                                        <div class="notif-img">
                                            <img src="{{ asset('template/kaiadmin/img/jm_denis.jpg') }}" alt="Img Profile" />
                                        </div>
                                        <div class="notif-content">
                                            <span class="subject">Admin PMB</span>
                                            <span class="block">Pendaftar baru telah mendaftar</span>
                                            <span class="time">5 menit lalu</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-img">
                                            <img src="{{ asset('template/kaiadmin/img/chadengle.jpg') }}" alt="Img Profile" />
                                        </div>
                                        <div class="notif-content">
                                            <span class="subject">Sistem</span>
                                            <span class="block">Backup data berhasil</span>
                                            <span class="time">12 menit lalu</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="javascript:void(0);">Lihat semua pesan<i class="fa fa-angle-right"></i></a>
                        </li>
                    </ul>
                </li>

                <!-- Notifications -->
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        <span class="notification">4</span>
                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">Anda memiliki 4 notifikasi baru</div>
                        </li>
                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">
                                    <a href="#">
                                        <div class="notif-icon notif-primary">
                                            <i class="fa fa-user-plus"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block">Pendaftar baru terdaftar</span>
                                            <span class="time">5 menit lalu</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-icon notif-success">
                                            <i class="fa fa-money-bill"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block">Pembayaran berhasil diverifikasi</span>
                                            <span class="time">12 menit lalu</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-icon notif-warning">
                                            <i class="fa fa-clock"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block">Periode pendaftaran akan berakhir</span>
                                            <span class="time">1 jam lalu</span>
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="notif-icon notif-danger">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block">Kuota hampir penuh</span>
                                            <span class="time">2 jam lalu</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="see-all" href="javascript:void(0);">Lihat semua notifikasi<i class="fa fa-angle-right"></i></a>
                        </li>
                    </ul>
                </li>

                <!-- Quick Actions -->
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fas fa-layer-group"></i>
                    </a>
                    <div class="dropdown-menu quick-actions animated fadeIn">
                        <div class="quick-actions-header">
                            <span class="title mb-1">Aksi Cepat</span>
                            <span class="subtitle op-7">Pintasan</span>
                        </div>
                        <div class="quick-actions-scroll scrollbar-outer">
                            <div class="quick-actions-items">
                                <div class="row m-0">
                                    <a class="col-6 col-md-4 p-0" href="{{ route('jalur-pendaftaran.create') }}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-danger rounded-circle">
                                                <i class="fas fa-route"></i>
                                            </div>
                                            <span class="text">Jalur Baru</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="{{ route('gelombang.create') }}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-warning rounded-circle">
                                                <i class="fas fa-water"></i>
                                            </div>
                                            <span class="text">Gelombang</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="{{ route('biaya-pendaftaran.create') }}">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-info rounded-circle">
                                                <i class="fas fa-money-bill"></i>
                                            </div>
                                            <span class="text">Biaya</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="#">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-success rounded-circle">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                            <span class="text">Laporan</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="#">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-primary rounded-circle">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <span class="text">Pendaftar</span>
                                        </div>
                                    </a>
                                    <a class="col-6 col-md-4 p-0" href="#">
                                        <div class="quick-actions-item">
                                            <div class="avatar-item bg-secondary rounded-circle">
                                                <i class="fas fa-cog"></i>
                                            </div>
                                            <span class="text">Pengaturan</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- User Profile -->
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm">
                            <img src="{{ asset('template/kaiadmin/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">Administrator</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        <img src="{{ asset('template/kaiadmin/img/profile.jpg') }}" alt="image profile" class="avatar-img rounded" />
                                    </div>
                                    <div class="u-text">
                                        <h4>Administrator</h4>
                                        <p class="text-muted">admin@siakad.ac.id</p>
                                        <a href="#" class="btn btn-xs btn-secondary btn-sm">Lihat Profile</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Profile Saya</a>
                                <a class="dropdown-item" href="#">Pengaturan Akun</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item w-100 text-start">
                                        Logout
                                    </button>
                                </form>

                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>