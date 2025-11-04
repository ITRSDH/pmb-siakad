@extends('admin.layouts.app')

@section('title', 'Laporan Data Pendaftar')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Laporan Data Pendaftar</h3>
            <h6 class="op-7 mb-2">Laporan lengkap data pendaftar dengan filter dan export</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <button type="button" class="btn btn-success btn-round" onclick="exportData()">
                <span class="btn-label"><i class="fa fa-download"></i></span>
                Export CSV
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <!-- Statistics Cards -->
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Pendaftar</p>
                                <h4 class="card-title">{{ $stats['total'] }}</h4>
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
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Terverifikasi</p>
                                <h4 class="card-title">{{ $stats['submitted'] }}</h4>
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
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Lunas</p>
                                <h4 class="card-title">{{ $stats['lunas'] }}</h4>
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
                                <i class="fas fa-venus-mars"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Jenis Kelamin</p>
                                <h4 class="card-title">L: {{ $stats['laki_laki'] }} | P: {{ $stats['perempuan'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Data Pendaftar
                        </div>
                        <div class="card-tools">
                            <button class="btn btn-icon btn-clean me-0 {{ request()->hasAny(['periode_id', 'status', 'status_pembayaran', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']) ? 'btn-primary' : '' }}" type="button" onclick="toggleFilter()">
                                <i class="fas fa-filter"></i>
                                @if(request()->hasAny(['periode_id', 'status', 'status_pembayaran', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']))
                                    <span class="badge badge-danger badge-sm">{{ collect(request()->only(['periode_id', 'status', 'status_pembayaran', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']))->filter()->count() }}</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Panel -->
                    <div class="collapse {{ request()->hasAny(['periode_id', 'status', 'status_pembayaran', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']) ? 'show' : '' }}" id="filterPanel">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h4 class="card-title">Filter Laporan</h4>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.laporan.pendaftar') }}" id="filterForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="periode_id">Periode Pendaftaran</label>
                                                <select name="periode_id" id="periode_id" class="form-select">
                                                    <option value="">-- Semua Periode --</option>
                                                    @foreach($periodes as $periode)
                                                        <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                                            {{ $periode->nama_periode }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status Pendaftaran</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">-- Semua Status --</option>
                                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status_pembayaran">Status Pembayaran</label>
                                                <select name="status_pembayaran" id="status_pembayaran" class="form-select">
                                                    <option value="">-- Semua Status --</option>
                                                    <option value="confirmed" {{ request('status_pembayaran') == 'confirmed' ? 'selected' : '' }}>Lunas</option>
                                                    <option value="pending" {{ request('status_pembayaran') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                                    <option value="rejected" {{ request('status_pembayaran') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                                    <option value="belum_bayar" {{ request('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                                                    <option value="">-- Semua --</option>
                                                    <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal_mulai">Tanggal Mulai</label>
                                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal_selesai">Tanggal Selesai</label>
                                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Filter
                                                </button>
                                                @if(request()->hasAny(['periode_id', 'status', 'status_pembayaran', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']))
                                                    <a href="{{ route('admin.laporan.pendaftar') }}" class="btn btn-secondary">
                                                        <i class="fas fa-times"></i> Reset
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if ($pendaftars->count() > 0)
                        <div class="table-responsive">
                            <table id="laporanTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Nomor Pendaftaran</th>
                                        <th>Nama Lengkap</th>
                                        <th>NIK</th>
                                        <th>Email</th>
                                        <th>JK</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Status Bayar</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendaftars as $p)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $p->nomor_pendaftaran }}</strong></td>
                                            <td>{{ $p->nama_lengkap }}</td>
                                            <td>{{ $p->nik ?? '-' }}</td>
                                            <td>{{ $p->email }}</td>
                                            <td>
                                                @if($p->jenis_kelamin == 'L')
                                                    <span class="badge badge-info">L</span>
                                                @elseif($p->jenis_kelamin == 'P')
                                                    <span class="badge badge-warning">P</span>
                                                @else
                                                    <span class="badge badge-secondary">-</span>
                                                @endif
                                            </td>
                                            <td>{{ optional($p->periodePendaftaran)->nama_periode ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $p->status == 'submitted' ? 'success' : ($p->status == 'draft' ? 'warning' : 'danger') }}">
                                                    @if($p->status == 'submitted')
                                                        Terverifikasi
                                                    @elseif($p->status == 'draft')
                                                        Draft
                                                    @elseif($p->status == 'rejected')
                                                        Ditolak
                                                    @else
                                                        {{ ucfirst($p->status) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $latest = $p->payments->sortByDesc('created_at')->first();
                                                    if ($latest?->status === 'confirmed') {
                                                        $statusBayar = 'Lunas';
                                                        $badgeBayar = 'success';
                                                    } elseif ($latest?->status === 'pending') {
                                                        $statusBayar = 'Menunggu';
                                                        $badgeBayar = 'warning';
                                                    } elseif ($latest?->status === 'rejected') {
                                                        $statusBayar = 'Ditolak';
                                                        $badgeBayar = 'danger';
                                                    } else {
                                                        $statusBayar = 'Belum';
                                                        $badgeBayar = 'secondary';
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $badgeBayar }}">
                                                    {{ $statusBayar }}
                                                </span>
                                            </td>
                                            <td>{{ $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-chart-bar fa-5x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">Tidak ada data pendaftar</h4>
                            <p class="text-muted mb-4">
                                @if(request()->hasAny(['periode_id', 'status', 'status_pembayaran', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']))
                                    Tidak ada data yang sesuai dengan filter yang dipilih.
                                @else
                                    Belum ada data pendaftar yang tercatat.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        #filterPanel {
            transition: all 0.3s ease;
        }
        #filterPanel.show {
            display: block !important;
        }
        .card-tools .btn-icon {
            padding: 8px 12px;
            position: relative;
        }
        .card-tools .btn-icon .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 10px;
            min-width: 18px;
            height: 18px;
            line-height: 18px;
            border-radius: 50%;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#laporanTable').DataTable({
                "pageLength": 25,
                "searching": true,
                "paging": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

            // Show filter panel if any filter is active
            @if(request()->hasAny(['periode_id', 'status', 'status_pembayaran', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']))
                $('#filterPanel').addClass('show');
            @endif
        });

        function toggleFilter() {
            const filterPanel = document.getElementById('filterPanel');
            
            // Try Bootstrap collapse first
            if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                const bsCollapse = new bootstrap.Collapse(filterPanel, {
                    toggle: true
                });
            } else {
                // Fallback to manual toggle
                if (filterPanel.classList.contains('show')) {
                    filterPanel.classList.remove('show');
                } else {
                    filterPanel.classList.add('show');
                }
            }
        }

        function exportData() {
            // Get current filter parameters
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            
            // Create export URL with current filters
            const exportUrl = "{{ route('admin.laporan.pendaftar.export') }}" + '?' + params.toString();
            
            // Download the file
            window.location.href = exportUrl;
        }
    </script>
@endpush
