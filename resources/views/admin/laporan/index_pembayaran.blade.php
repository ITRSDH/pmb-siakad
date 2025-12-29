@extends('admin.layouts.app')

@section('title', 'Laporan Pembayaran')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Laporan Pembayaran</h3>
            <h6 class="op-7 mb-2">Laporan pembayaran pendaftar dengan filter dan export</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <button type="button" class="btn btn-success btn-round" onclick="exportData()">
                <span class="btn-label"><i class="fa fa-download"></i></span>
                Export CSV
            </button>
        </div>
    </div>

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
                                <p class="card-category">Total Pembayaran</p>
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
                                <p class="card-category">Terkonfirmasi</p>
                                <h4 class="card-title">{{ $stats['confirmed'] }}</h4>
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
                                <p class="card-category">Menunggu</p>
                                <h4 class="card-title">{{ $stats['pending'] }}</h4>
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
                            Data Pembayaran
                        </div>
                        <div class="card-tools">
                            <button class="btn btn-icon btn-clean me-0" type="button" onclick="toggleFilter()">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
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

                    <div class="collapse" id="filterPanel">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h4 class="card-title">Filter Laporan</h4>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.laporan.pembayaran') }}" id="filterForm">
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
                                                <label for="status">Status Pembayaran</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">-- Semua Status --</option>
                                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Lunas</option>
                                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal_mulai">Tanggal Mulai</label>
                                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tanggal_selesai">Tanggal Selesai</label>
                                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search"></i> Filter
                                                </button>
                                                @if(request()->hasAny(['periode_id', 'status', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']))
                                                    <a href="{{ route('admin.laporan.pembayaran') }}" class="btn btn-secondary">
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

                    @if ($pembayarans->count() > 0)
                        <div class="table-responsive">
                            <table id="laporanTable" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Nomor Pendaftaran</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>No HP</th>
                                        <th>Periode</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembayarans as $index => $p)
                                        <tr>
                                            <td>{{ $pembayarans->firstItem() + $index }}</td>
                                            <td>{{ optional($p->pendaftar)->nomor_pendaftaran ?? '-' }}</td>
                                            <td>{{ optional($p->pendaftar)->nama_lengkap ?? '-' }}</td>
                                            <td>{{ optional($p->pendaftar)->email ?? '-' }}</td>
                                            <td>{{ optional($p->pendaftar)->no_hp ?? '-' }}</td>
                                            <td>{{ optional(optional($p->pendaftar)->periodePendaftaran)->nama_periode ?? '-' }}</td>
                                            <td>{{ $p->tanggal_pembayaran ? \Carbon\Carbon::parse($p->tanggal_pembayaran)->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $p->metode_pembayaran }}</td>
                                            <td>
                                                <span class="badge badge-{{ $p->status == 'confirmed' ? 'success' : ($p->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($p->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $p->catatan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- DataTables-style Pagination -->
                        @if($pembayarans->hasPages())
                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" role="status" aria-live="polite">
                                        Menampilkan {{ $pembayarans->firstItem() }} sampai {{ $pembayarans->lastItem() }} dari {{ $pembayarans->total() }} data
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination justify-content-end">
                                            {{-- Previous Button --}}
                                            @if ($pembayarans->onFirstPage())
                                                <li class="paginate_button page-item previous disabled">
                                                    <span class="page-link">Sebelumnya</span>
                                                </li>
                                            @else
                                                <li class="paginate_button page-item previous">
                                                    <a href="{{ $pembayarans->previousPageUrl() }}" class="page-link">Sebelumnya</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($pembayarans->getUrlRange(1, $pembayarans->lastPage()) as $page => $url)
                                                @if ($page == $pembayarans->currentPage())
                                                    <li class="paginate_button page-item active">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="paginate_button page-item">
                                                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($pembayarans->hasMorePages())
                                                <li class="paginate_button page-item next">
                                                    <a href="{{ $pembayarans->nextPageUrl() }}" class="page-link">Selanjutnya</a>
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
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-chart-bar fa-5x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">Tidak ada data pembayaran</h4>
                            <p class="text-muted mb-4">
                                @if(request()->hasAny(['periode_id', 'status', 'jenis_kelamin', 'tanggal_mulai', 'tanggal_selesai']))
                                    Tidak ada data yang sesuai dengan filter yang dipilih.
                                @else
                                    Belum ada data pembayaran yang tercatat.
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
    <script>
        $(document).ready(function() {
            // DataTable dengan fitur minimal - Laravel pagination yang handle
            $('#laporanTable').DataTable({
                "paging": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [0] }
                ]
            });
        });

        // Show loading overlay function
        function showLoading() {
            $('#loadingOverlay').css('display', 'flex');
        }

        // Show loading on filter form submit
        $('#filterForm').on('submit', function() {
            showLoading();
        });

        // Show loading when clicking pagination links
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            showLoading();
            window.location.href = $(this).attr('href');
        });

        function toggleFilter() {
            const filterPanel = document.getElementById('filterPanel');
            if (filterPanel.classList.contains('show')) {
                filterPanel.classList.remove('show');
            } else {
                filterPanel.classList.add('show');
            }
        }

        function exportData() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const exportUrl = "{{ route('admin.laporan.pembayaran.export') }}" + '?' + params.toString();
            window.location.href = exportUrl;
        }
    </script>
@endpush
