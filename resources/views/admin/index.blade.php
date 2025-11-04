@extends('admin.layouts.app')

@section('title', 'Dashboard SIAKAD PMB')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Dashboard PMB</h3>
        <h6 class="op-7 mb-2">Sistem Informasi Penerimaan Mahasiswa Baru</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('jalur-pendaftaran.index') }}" class="btn btn-label-info btn-round me-2">
            <span class="btn-label"><i class="fa fa-route"></i></span>
            Kelola PMB
        </a>
        <a href="{{ route('biaya-pendaftaran.create') }}" class="btn btn-primary btn-round">
            <span class="btn-label"><i class="fa fa-plus"></i></span>
            Tambah Data
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-route"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Jalur Pendaftaran</p>
                            <h4 class="card-title">{{ $jalur_count }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-info bubble-shadow-small">
                            <i class="fas fa-fire"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Gelombang</p>
                            <h4 class="card-title">{{ $gelombang_count }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-success bubble-shadow-small">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Biaya Pendaftaran</p>
                            <h4 class="card-title">{{ $biaya_count }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-secondary bubble-shadow-small">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Pendaftar</p>
                            <h4 class="card-title">{{ $total_pendaftar }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Quick Actions -->
<div class="row">
    <div class="col-md-8">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Statistik Pendaftaran</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                            <span class="btn-label"><i class="fa fa-pencil"></i></span>
                            Export
                        </a>
                        <a href="#" class="btn btn-label-info btn-round btn-sm">
                            <span class="btn-label"><i class="fa fa-print"></i></span>
                            Print
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="min-height: 375px">
                    <canvas id="statisticsChart"></canvas>
                </div>
                <div id="myChartLegend"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-primary card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Pendaftaran Hari Ini</div>
                    <div class="card-tools">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-label-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Export
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">PDF</a>
                                <a class="dropdown-item" href="#">Excel</a>
                                <a class="dropdown-item" href="#">CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-category">{{ date('d F Y') }}</div>
            </div>
            <div class="card-body pb-0">
                <div class="mb-4 mt-2">
                    <h1>{{ $today_registrations }}</h1>
                </div>
                <div class="pull-in">
                    <canvas id="dailyRegistrationChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="card card-round">
            <div class="card-body pb-0">
                <div class="h1 fw-bold float-end text-primary">+{{ $today_registrations > 0 ? number_format(($today_registrations / max($total_pendaftar, 1)) * 100, 1) : '0' }}%</div>
                <h2 class="mb-2">{{ $total_pendaftar }}</h2>
                <p class="text-muted">Total pendaftar terdaftar</p>
                <div class="pull-in sparkline-fix">
                    <div id="lineChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Menu -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row card-tools-still-right">
                    <h4 class="card-title">Menu Akses Cepat</h4>
                    <div class="card-tools">
                        <button class="btn btn-icon btn-link btn-primary btn-xs">
                            <span class="fa fa-angle-down"></span>
                        </button>
                        <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                            <span class="fa fa-sync-alt"></span>
                        </button>
                    </div>
                </div>
                <p class="card-category">
                    Akses cepat ke fitur-fitur utama sistem PMB
                </p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-light bg-primary-gradient">
                            <div class="card-body pb-0">
                                <div class="h1 text-white">
                                    <i class="fas fa-route"></i>
                                </div>
                                <h2 class="mb-2 fw-bold text-white">Jalur Pendaftaran</h2>
                                <p class="text-white">Kelola jalur pendaftaran mahasiswa</p>
                                <div class="pull-right">
                                    <a href="{{ route('jalur-pendaftaran.index') }}" class="btn btn-light btn-round">
                                        Kelola
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-light bg-info-gradient">
                            <div class="card-body pb-0">
                                <div class="h1 text-white">
                                    <i class="fas fa-water"></i>
                                </div>
                                <h2 class="mb-2 fw-bold text-white">Gelombang</h2>
                                <p class="text-white">Kelola gelombang pendaftaran</p>
                                <div class="pull-right">
                                    <a href="{{ route('gelombang.index') }}" class="btn btn-light btn-round">
                                        Kelola
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-light bg-success-gradient">
                            <div class="card-body pb-0">
                                <div class="h1 text-white">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <h2 class="mb-2 fw-bold text-white">Biaya</h2>
                                <p class="text-white">Kelola biaya pendaftaran</p>
                                <div class="pull-right">
                                    <a href="{{ route('biaya-pendaftaran.index') }}" class="btn btn-light btn-round">
                                        Kelola
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card card-light bg-warning-gradient">
                            <div class="card-body pb-0">
                                <div class="h1 text-white">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h2 class="mb-2 fw-bold text-white">Pendaftar</h2>
                                <p class="text-white">Data mahasiswa pendaftar</p>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-light btn-round">
                                        Lihat
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
@endsection

@push('scripts')
<script>
    // Debug: Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        alert('Chart library tidak tersedia. Pastikan Chart.js sudah di-load.');
    }

    // Data dari controller dengan fallback
    const monthlyData = @json($monthlyRegistrations) || {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    };
    
    console.log('Monthly Data:', monthlyData); // Debug log
    
    // Chart pendaftar per bulan (Line Chart)
    const statisticsChart = document.getElementById('statisticsChart').getContext('2d');
    try {
        const myStatisticsChart = new Chart(statisticsChart, {
            type: 'line',
            data: {
                labels: monthlyData.labels,
                datasets: [{
                    label: "Pendaftar",
                    borderColor: '#177dff',
                    pointBackgroundColor: 'rgba(23, 125, 255, 1)',
                    pointRadius: 4,
                    backgroundColor: 'rgba(23, 125, 255, 0.4)',
                    fill: true,
                    borderWidth: 2,
                    data: monthlyData.data
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                layout: {
                    padding: { left: 15, right: 15, top: 15, bottom: 15 }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: "rgba(0,0,0,0.5)",
                            maxTicksLimit: 5,
                            padding: 20
                        },
                        grid: {
                            drawTicks: false,
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            color: "transparent"
                        },
                        ticks: {
                            padding: 20,
                            color: "rgba(0,0,0,0.5)"
                        }
                    }
                }
            }
        });
        console.log('Statistics chart created successfully');
    } catch (error) {
        console.error('Error creating statistics chart:', error);
        document.getElementById('statisticsChart').parentElement.innerHTML = 
            '<div class="alert alert-warning">Chart tidak dapat dimuat. Error: ' + error.message + '</div>';
    }

    // Daily registration chart (simplified line chart untuk 7 hari terakhir)
    const dailyChart = document.getElementById('dailyRegistrationChart').getContext('2d');
    
    // Generate data untuk 7 hari terakhir
    const last7Days = [];
    const dailyData = [];
    const todayRegistrations = {{ $today_registrations ?? 0 }};
    
    for(let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        last7Days.push(date.getDate());
        // Sample data - bisa diganti dengan query real dari database
        dailyData.push(Math.floor(Math.random() * (todayRegistrations + 5)));
    }
    
    try {
        const myDailyChart = new Chart(dailyChart, {
            type: 'line',
            data: {
                labels: last7Days,
                datasets: [{
                    borderColor: '#ffffff',
                    pointBorderColor: '#ffffff',
                    pointBackgroundColor: '#ffffff',
                    pointRadius: 2,
                    fill: false,
                    borderWidth: 2,
                    data: dailyData
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                },
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                }
            }
        });
        console.log('Daily chart created successfully');
    } catch (error) {
        console.error('Error creating daily chart:', error);
    }

    // Sparkline untuk trend menggunakan data 7 bulan terakhir
    try {
        const sparklineData = monthlyData.data.slice(-7);
        console.log('Sparkline data:', sparklineData);
        
        if (typeof $.fn.sparkline !== 'undefined') {
            $("#lineChart").sparkline(sparklineData, {
                type: "line",
                height: "70",
                width: "100%",
                lineWidth: "2",
                lineColor: "#177dff",
                fillColor: "rgba(23, 125, 255, 0.14)",
            });
            console.log('Sparkline created successfully');
        } else {
            console.warn('jQuery Sparkline plugin not loaded');
            $("#lineChart").html('<div class="text-center text-muted">Sparkline unavailable</div>');
        }
    } catch (error) {
        console.error('Error creating sparkline:', error);
    }

    // Legend untuk chart utama
    $('#myChartLegend').empty();
    $('#myChartLegend').append('<div class="row">' +
        '<div class="col-6">' +
            '<div class="d-flex align-items-center">' +
                '<span class="legend-indicator" style="background-color: #177dff; width: 12px; height: 12px; border-radius: 2px; margin-right: 8px;"></span>' +
                '<span class="legend-text">Pendaftar per Bulan</span>' +
            '</div>' +
        '</div>' +
        '<div class="col-6 text-end">' +
            '<span class="text-muted">Total: {{ $total_pendaftar }} pendaftar</span>' +
        '</div>' +
    '</div>');
</script>
@endpush