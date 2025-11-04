<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'SIAKAD - Admin Dashboard')</title>
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

    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        <!-- End Sidebar -->

        <div class="main-panel">
            <!-- Main Header -->
            @include('admin.layouts.header')
            <!-- End Main Header -->

            <!-- Main Content -->
            <div class="container">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>
            <!-- End Main Content -->

            <!-- Footer -->
            @include('admin.layouts.footer')
            <!-- End Footer -->
        </div>

        <!-- Settings Panel -->
        @include('admin.layouts.settings')
        <!-- End Settings Panel -->
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('template/kaiadmin/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('template/kaiadmin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('template/kaiadmin/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('template/kaiadmin/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS - ENABLED: Digunakan untuk dashboard analytics -->
    <script src="{{ asset('template/kaiadmin/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline - ENABLED: Digunakan untuk mini charts di dashboard -->  
    <script src="{{ asset('template/kaiadmin/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle - DISABLED: Tidak ada circular charts -->
    <!-- <script src="{{ asset('template/kaiadmin/js/plugin/chart-circle/circles.min.js') }}"></script> -->

    <!-- Datatables - ACTIVE: Digunakan di semua index views -->
    <script src="{{ asset('template/kaiadmin/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify - DISABLED: Menggunakan SweetAlert untuk notifikasi -->
    <!-- <script src="{{ asset('template/kaiadmin/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script> -->

    <!-- jQuery Vector Maps - DISABLED: Tidak ada map functionality -->
    <!-- <script src="{{ asset('template/kaiadmin/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script> -->
    <!-- <script src="{{ asset('template/kaiadmin/js/plugin/jsvectormap/world.js') }}"></script> -->

    <!-- Sweet Alert - ACTIVE: Digunakan untuk confirmations -->
    <script src="{{ asset('template/kaiadmin/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS - ACTIVE: Core functionality -->
    <script src="{{ asset('template/kaiadmin/js/kaiadmin.min.js') }}"></script>

    <!-- Kaiadmin DEMO - DISABLED: Demo scripts tidak diperlukan production -->
    <!-- <script src="{{ asset('template/kaiadmin/js/setting-demo.js') }}"></script> -->
    <!-- <script src="{{ asset('template/kaiadmin/js/demo.js') }}"></script> -->

    <!-- Conditional Scripts - Load only when needed -->
    @stack('conditional-scripts')

    <!-- Page Specific Scripts -->  
    @stack('scripts')

    <!-- Performance Analytics (Optional - Enable for monitoring) -->
    <script>
        // Performance monitoring
        window.addEventListener('load', function() {
            if (performance.navigation.type === performance.navigation.TYPE_RELOAD) {
                console.log('Page reloaded - consider optimizing');
            }
            
            // Log load time untuk monitoring
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            if (loadTime > 3000) {
                console.warn(`Slow page load: ${loadTime}ms - consider optimization`);
            }
        });
    </script>
</body>
</html>