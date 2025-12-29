@extends('admin.layouts.app')

@section('title', 'Periode Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Periode Pendaftaran</h3>
        <h6 class="op-7 mb-2">Kelola periode pendaftaran mahasiswa baru</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('periode-pendaftaran.create') }}" class="btn btn-label-primary btn-round">
            <span class="btn-label"><i class="fa fa-plus"></i></span>
            Tambah Periode
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        Data Periode Pendaftaran
                    </h4>
                </div>
            </div>
            <div class="card-body">
                <!-- Loading Overlay -->
                <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
                    <div class="text-center">
                        <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-white mt-3 fw-bold">Memuat data...</p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Search Form -->
                <div class="row mb-4">
                    <div class="col-md-10">
                        <form method="GET" action="{{ route('periode-pendaftaran.index') }}" id="searchForm">
                            <label for="search" class="form-label">Cari Periode Pendaftaran</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Nama periode, gelombang, atau jalur..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('periode-pendaftaran.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 text-end d-flex align-items-end justify-content-end">
                        <div>
                            <small class="text-muted d-block">Total Data</small>
                            <h4 class="mb-0 fw-bold text-primary">{{ $periodePendaftarans->total() }}</h4>
                            @if(request('search'))
                                <small class="badge badge-info">Hasil Pencarian</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="periodeTable" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Periode</th>
                                <th>Gelombang</th>
                                <th>Jalur Pendaftaran</th>
                                <th>Tanggal</th>
                                <th>Biaya</th>
                                <th>Kuota</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($periodePendaftarans as $index => $periode)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <span class="avatar-title rounded-circle bg-primary text-white">
                                                    <i class="fas fa-calendar-day"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $periode->nama_periode }}</h6>
                                                @if($periode->deskripsi)
                                                    <small class="text-muted">{{ Str::limit($periode->deskripsi, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <i class="fa fa-water"></i>
                                            {{ $periode->gelombang->nama_gelombang }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            <i class="fa fa-route"></i>
                                            {{ $periode->jalurPendaftaran->nama_jalur }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="fw-bold text-success">{{ $periode->tanggal_mulai->format('d M') }}</div>
                                            <small class="text-muted">s/d {{ $periode->tanggal_selesai->format('d M Y') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            <i class="fas fa-rupiah-sign"></i>
                                            {{ number_format($periode->biayaPendaftaran->jumlah_biaya, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-{{ $periode->persentase_kuota >= 80 ? 'danger' : ($periode->persentase_kuota >= 50 ? 'warning' : 'success') }}" 
                                                 style="width: {{ $periode->persentase_kuota }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $periode->kuota_terisi }}/{{ $periode->kuota }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-{{ $periode->status_badge }}">
                                            @if($periode->status === 'aktif')
                                                <i class="fa fa-check-circle me-1"></i>
                                            @elseif($periode->status === 'draft')
                                                <i class="fa fa-edit me-1"></i>
                                            @elseif($periode->status === 'selesai')
                                                <i class="fa fa-flag-checkered me-1"></i>
                                            @else
                                                <i class="fa fa-times-circle me-1"></i>
                                            @endif
                                            {{ ucfirst($periode->status) }}
                                        </span>
                                        
                                        @if($periode->is_pending)
                                            <br><small class="badge badge-warning mt-1">
                                                <i class="fa fa-hourglass-half me-1"></i>Pending
                                            </small>
                                        @elseif($periode->is_berjalan)
                                            <br><small class="badge badge-success mt-1">
                                                <i class="fa fa-clock me-1"></i>Berjalan
                                            </small>
                                        @elseif($periode->is_expired)
                                            <br><small class="badge badge-secondary mt-1">
                                                <i class="fa fa-calendar-times me-1"></i>Selesai
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-button-action">
                                            <a href="{{ route('periode-pendaftaran.show', $periode) }}" 
                                               class="btn btn-link btn-info btn-lg" 
                                               data-bs-toggle="tooltip" 
                                               title="Lihat Detail">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('periode-pendaftaran.edit', $periode) }}" 
                                               class="btn btn-link btn-primary btn-lg" 
                                               data-bs-toggle="tooltip" 
                                               title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-link btn-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Hapus"
                                                    onclick="confirmDelete('{{ $periode->id }}', '{{ $periode->nama_periode }}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum Ada Periode Pendaftaran</h5>
                                            <p class="text-muted">Silakan tambah periode pendaftaran baru.</p>
                                            <a href="{{ route('periode-pendaftaran.create') }}" class="btn btn-primary">
                                                <i class="fa fa-plus"></i> Tambah Periode Pendaftaran
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- DataTables-style Pagination -->
                @if($periodePendaftarans->hasPages())
                    <div class="row mt-4">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" role="status" aria-live="polite">
                                Menampilkan {{ $periodePendaftarans->firstItem() }} sampai {{ $periodePendaftarans->lastItem() }} dari {{ $periodePendaftarans->total() }} data
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers">
                                <ul class="pagination justify-content-end">
                                    {{-- Previous Button --}}
                                    @if ($periodePendaftarans->onFirstPage())
                                        <li class="paginate_button page-item previous disabled">
                                            <span class="page-link">Sebelumnya</span>
                                        </li>
                                    @else
                                        <li class="paginate_button page-item previous">
                                            <a href="{{ $periodePendaftarans->appends(request()->query())->previousPageUrl() }}" class="page-link">Sebelumnya</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($periodePendaftarans->getUrlRange(1, $periodePendaftarans->lastPage()) as $page => $url)
                                        @if ($page == $periodePendaftarans->currentPage())
                                            <li class="paginate_button page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="paginate_button page-item">
                                                <a href="{{ $periodePendaftarans->appends(request()->query())->url($page) }}" class="page-link">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Button --}}
                                    @if ($periodePendaftarans->hasMorePages())
                                        <li class="paginate_button page-item next">
                                            <a href="{{ $periodePendaftarans->appends(request()->query())->nextPageUrl() }}" class="page-link">Selanjutnya</a>
                                        </li>
                                    @else
                                        <li class="paginate_button page-item next disabled">
                                            <span class="page-link">Selanjutnya</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Form for Delete -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // DataTable dengan fitur minimal - Laravel pagination yang handle
    $('#periodeTable').DataTable({
        "paging": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": [0, 8] },
            { "width": "5%", "targets": [0] },
            { "width": "20%", "targets": [1] },
            { "width": "10%", "targets": [2] },
            { "width": "15%", "targets": [3] },
            { "width": "15%", "targets": [4] },
            { "width": "10%", "targets": [5] },
            { "width": "10%", "targets": [6] },
            { "width": "10%", "targets": [7] },
            { "width": "10%", "targets": [8] }
        ]
    });

    // Show loading overlay function
    function showLoading() {
        $('#loadingOverlay').css('display', 'flex');
    }

    // Show loading on search submit
    $('#searchForm').on('submit', function() {
        showLoading();
    });

    // Show loading when clicking pagination links
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        showLoading();
        window.location.href = $(this).attr('href');
    });
});

function confirmDelete(id, nama) {
    swal({
        title: "Hapus Periode Pendaftaran?",
        text: `Periode "${nama}" akan dihapus secara permanen! Data ini tidak dapat dikembalikan.`,
        type: "warning",
        buttons: {
            cancel: {
                visible: true,
                text: "Batal",
                className: "btn btn-danger"
            },
            confirm: {
                text: "Ya, Hapus!",
                className: "btn btn-success"
            }
        }
    }).then((willDelete) => {
        if (willDelete) {
            const form = document.getElementById('deleteForm');
            form.action = `/periode-pendaftaran/${id}`;
            form.submit();
        }
    });
}
</script>
@endpush