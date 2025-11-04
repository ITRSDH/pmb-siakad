<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard.index') }}" class="logo">
                {{-- <img src="{{ asset('template/kaiadmin/img/kaiadmin/logo_light.svg') }}" alt="SIAKAD Logo" class="navbar-brand" height="20" /> --}}
                <div class="d-flex align-items-center" style="gap:0.75rem;">
                    <div class="sidebar-brand" style="line-height:1;">
                        <span style="display:block;font-weight:700;font-size:1rem;letter-spacing:0.4px;color:#fff;">
                            PMB SIAKAD
                        </span>
                        <small style="display:block;font-size:11px;opacity:0.85;color:#dfe7ff;">
                            Penerimaan Mahasiswa Baru
                        </small>
                    </div>
                </div>
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
    
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Master Data PMB -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Master Data PMB</h4>
                </li>

                <!-- Jalur Pendaftaran -->
                <li class="nav-item {{ request()->routeIs('jalur-pendaftaran*') ? 'active' : '' }}">
                    <a href="{{ route('jalur-pendaftaran.index') }}">
                        <i class="fas fa-route"></i>
                        <p>Jalur Pendaftaran</p>
                    </a>
                </li>

                <!-- Gelombang -->
                <li class="nav-item {{ request()->routeIs('gelombang*') ? 'active' : '' }}">
                    <a href="{{ route('gelombang.index') }}">
                        <i class="fas fa-fire"></i>
                        <p>Gelombang</p>
                    </a>
                </li>

                <!-- Biaya Pendaftaran -->
                <li class="nav-item {{ request()->routeIs('biaya-pendaftaran*') ? 'active' : '' }}">
                    <a href="{{ route('biaya-pendaftaran.index') }}">
                        <i class="fas fa-money-bill-wave"></i>
                        <p>Biaya Pendaftaran</p>
                    </a>
                </li>

                <!-- Periode Pendaftaran -->
                <li class="nav-item {{ request()->routeIs('periode-pendaftaran*') ? 'active' : '' }}">
                    <a href="{{ route('periode-pendaftaran.index') }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Periode Pendaftaran</p>
                    </a>
                </li>

                <!-- Registrasi Section -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Registrasi</h4>
                </li>

                <!-- Registrasi Mahasiswa -->
                <li class="nav-item {{ request()->routeIs('registrasi*') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fas fa-user-graduate"></i>
                        <p>Registrasi Mahasiswa</p>
                        <span class="badge badge-success">New</span>
                    </a>
                </li>

                <!-- Pendaftar -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#pendaftar">
                        <i class="fas fa-users"></i>
                        <p>Data Pendaftar</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="pendaftar">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('pendaftar.index') }}">
                                    <span class="sub-item">Pendaftar Menunggu</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pendaftar.diterima') }}">
                                    <span class="sub-item">Pendaftar Diterima</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Reports -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Laporan</h4>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#reports">
                        <i class="fas fa-chart-bar"></i>
                        <p>Laporan PMB</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.laporan*') ? 'show' : '' }}" id="reports">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.laporan.pendaftar') }}">
                                    <span class="sub-item">Laporan Pendaftar</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.laporan.pembayaran') }}">
                                    <span class="sub-item">Laporan Pembayaran</span>
                                </a>
                            </li>
                            {{-- <li>
                                <a href="#">
                                    <span class="sub-item">Laporan Per Gelombang</span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>

                <!-- Settings -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Pengaturan</h4>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-cog"></i>
                        <p>Pengaturan Sistem</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-file"></i>
                        <p>Dokumentasi</p>
                        <span class="badge badge-secondary">Help</span>
                    </a>
                </li>

                <!-- Divider -->
                <li style="margin: 1rem 0; border-top: 1px solid rgba(255, 255, 255, 0.1);"></li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ff6b6b;">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>