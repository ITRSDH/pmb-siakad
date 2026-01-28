@extends('admin.layouts.app')

@section('title', 'Laporan Lengkap Pendaftar dan Pembayaran')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Laporan Lengkap Pendaftar dan Pembayaran</h3>
            <h6 class="op-7 mb-2">Laporan gabungan data pendaftar dan pembayaran dengan filter bulan</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <button type="button" class="btn btn-success btn-round me-2" onclick="exportData()">
                <span class="btn-label"><i class="fa fa-file-excel"></i></span>
                Export CSV
            </button>
            <button type="button" class="btn btn-primary btn-round" onclick="exportExcel()">
                <span class="btn-label"><i class="fa fa-file-excel"></i></span>
                Export Excel
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('admin.laporan.index_all') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="periode_id" class="form-label">Periode Pendaftaran</label>
                        <select id="periode_id" name="periode_id" class="form-select">
                            <option value="">Semua Periode</option>
                            @foreach($periodes as $periode)
                                <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select id="bulan" name="bulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select id="tahun" name="tahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status Pendaftaran</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                        <select id="status_pembayaran" name="status_pembayaran" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="confirmed" {{ request('status_pembayaran') == 'confirmed' ? 'selected' : '' }}>Sudah Bayar</option>
                            <option value="pending" {{ request('status_pembayaran') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="rejected" {{ request('status_pembayaran') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="belum_bayar" {{ request('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.laporan.index_all') }}" class="btn btn-secondary">
                            <i class="fa fa-refresh"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
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
                                <p class="card-category">Sudah Bayar</p>
                                <h4 class="card-title">{{ $stats['confirmed_payment'] }}</h4>
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
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Menunggu Verifikasi</p>
                                <h4 class="card-title">{{ $stats['pending_payment'] }}</h4>
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
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Belum Bayar</p>
                                <h4 class="card-title">{{ $stats['belum_bayar'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Data Laporan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="laporanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Pendaftaran</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Periode</th>
                            <th>Jalur</th>
                            <th>Status Pendaftaran</th>
                            <th>Status Pembayaran</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftars as $index => $pendaftar)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pendaftar->nomor_pendaftaran }}</td>
                                <td>{{ $pendaftar->nama_lengkap }}</td>
                                <td>{{ $pendaftar->email }}</td>
                                <td>{{ $pendaftar->periodePendaftaran->nama_periode ?? '-' }}</td>
                                <td>{{ $pendaftar->periodePendaftaran->jalurPendaftaran->nama_jalur ?? '-' }}</td>
                                <td>
                                    @if($pendaftar->status == 'draft')
                                        <span class="badge badge-secondary">Draft</span>
                                    @elseif($pendaftar->status == 'submitted')
                                        <span class="badge badge-info">Submitted</span>
                                    @elseif($pendaftar->status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-warning">{{ $pendaftar->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pendaftar->payments->isNotEmpty())
                                        @php
                                            $latestPayment = $pendaftar->payments->sortByDesc('created_at')->first();
                                        @endphp
                                        @if($latestPayment->status == 'confirmed')
                                            <span class="badge badge-success">Sudah Bayar</span>
                                        @elseif($latestPayment->status == 'pending')
                                            <span class="badge badge-warning">Menunggu Verifikasi</span>
                                        @elseif($latestPayment->status == 'rejected')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">Belum Bayar</span>
                                    @endif
                                </td>
                                <td>{{ $pendaftar->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.laporan.show_all', $pendaftar->id) }}" 
                                                       class="btn btn-link btn-primary btn-lg" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Lihat Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function exportData() {
    // Dapatkan filter values
    const periodeId = document.getElementById('periode_id').value;
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    const status = document.getElementById('status').value;
    const statusPembayaran = document.getElementById('status_pembayaran').value;
    
    // Build URL dengan filter
    let url = "{{ route('admin.laporan.export_all') }}?";
    const params = new URLSearchParams();
    
    if (periodeId) params.append('periode_id', periodeId);
    if (bulan) params.append('bulan', bulan);
    if (tahun) params.append('tahun', tahun);
    if (status) params.append('status', status);
    if (statusPembayaran) params.append('status_pembayaran', statusPembayaran);
    
    url += params.toString();
    
    // Redirect ke export URL
    window.location.href = url;
}

function exportExcel() {
    // Dapatkan filter values
    const periodeId = document.getElementById('periode_id').value;
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;
    const status = document.getElementById('status').value;
    const statusPembayaran = document.getElementById('status_pembayaran').value;
    
    // Build URL dengan filter
    let url = "{{ route('admin.laporan.export_all_excel') }}?";
    const params = new URLSearchParams();
    
    if (periodeId) params.append('periode_id', periodeId);
    if (bulan) params.append('bulan', bulan);
    if (tahun) params.append('tahun', tahun);
    if (status) params.append('status', status);
    if (statusPembayaran) params.append('status_pembayaran', statusPembayaran);
    
    url += params.toString();
    
    // Redirect ke export URL
    window.location.href = url;
}

// Auto-submit form saat filter berubah
document.getElementById('filterForm').addEventListener('change', function(e) {
    if (e.target.tagName === 'SELECT') {
        setTimeout(() => {
            this.submit();
        }, 500);
    }
});
</script>
@endpush