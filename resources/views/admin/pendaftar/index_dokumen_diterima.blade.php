@extends('admin.layouts.app')

@section('title', 'Pendaftar Dokumen Diterima')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Data Pendaftar Dokumen Lengkap</h3>
            <h6 class="op-7 mb-2">Kelola data pendaftar yang telah melengkapi dokumen</h6>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Daftar Pendaftar Dokumen Lengkap</div>
                        <div class="card-tools">
                            <span class="badge badge-success">{{ $pendaftars->count() }} Pendaftar</span>
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

                    <!-- Filter & Search Form -->
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <form method="GET" action="{{ route('pendaftar.dokumen.diterima') }}" id="filterForm">
                                <label for="periode_id" class="form-label">Filter Periode</label>
                                <div class="d-flex">
                                    <select name="periode_id" id="periode_id" class="form-select me-2">
                                        <option value="">-- Semua Periode --</option>
                                        @foreach ($periodes as $periode)
                                            <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                                {{ $periode->nama_periode }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-5">
                            <form method="GET" action="{{ route('pendaftar.dokumen.diterima') }}" id="searchForm">
                                <label for="search" class="form-label">Cari Pendaftar</label>
                                <div class="input-group">
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Nomor, nama, atau email..." 
                                           value="{{ request('search') }}">
                                    <input type="hidden" name="periode_id" value="{{ request('periode_id') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    @if(request('search') || request('periode_id'))
                                        <a href="{{ route('pendaftar.dokumen.diterima') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="col-md-2 text-end d-flex align-items-end justify-content-end">
                            <div>
                                <small class="text-muted d-block">Total Data</small>
                                <h4 class="mb-0 fw-bold text-primary">{{ $pendaftars->total() }}</h4>
                                @if(request('search'))
                                    <small class="badge badge-info">Hasil Pencarian</small>
                                @elseif(request('periode_id'))
                                    <small class="badge badge-secondary">Terfilter</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($pendaftars->count() > 0)
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No. Pendaftaran</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Periode</th>
                                        <th>Kelengkapan Dokumen</th>
                                        <th>Status Pembayaran</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendaftars as $index => $pendaftar)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold">{{ $pendaftar->nomor_pendaftaran }}</span>
                                            </td>
                                            <td>{{ $pendaftar->nama_lengkap }}</td>
                                            <td>{{ $pendaftar->email }}</td>
                                            <td>
                                                <small class="text-muted">{{ $pendaftar->periodePendaftaran->nama_periode ?? 'N/A' }}</small><br>
                                                <span class="badge badge-info">{{ $pendaftar->periodePendaftaran->jalurPendaftaran->nama_jalur ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @if(isset($pendaftar->kelengkapan_dokumen))
                                                    <div class="d-flex flex-column">
                                                        @php
                                                            $isAllUploaded = $pendaftar->kelengkapan_dokumen['total_terupload'] == $pendaftar->kelengkapan_dokumen['total_diperlukan'];
                                                            $isWajibComplete = $pendaftar->kelengkapan_dokumen['wajib_terupload'] == $pendaftar->kelengkapan_dokumen['wajib_diperlukan'];
                                                        @endphp
                                                        
                                                        <div class="progress mb-1" style="height: 8px;">
                                                            <div class="progress-bar {{ $isAllUploaded ? 'bg-success' : 'bg-warning' }}" role="progressbar" 
                                                                 style="width: {{ $pendaftar->kelengkapan_dokumen['persentase'] }}%" 
                                                                 aria-valuenow="{{ $pendaftar->kelengkapan_dokumen['persentase'] }}" 
                                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        
                                                        <small class="text-muted">
                                                            {{ $pendaftar->kelengkapan_dokumen['total_terupload'] }}/{{ $pendaftar->kelengkapan_dokumen['total_diperlukan'] }} dokumen
                                                        </small>
                                                        <small class="text-muted">
                                                            Wajib: {{ $pendaftar->kelengkapan_dokumen['wajib_terupload'] }}/{{ $pendaftar->kelengkapan_dokumen['wajib_diperlukan'] }}
                                                        </small>
                                                        
                                                        @if($isAllUploaded)
                                                            <small class="text-success fw-bold">
                                                                <i class="fa fa-check-circle"></i> Dokumen telah diupload semua
                                                            </small>
                                                        @elseif($isWajibComplete)
                                                            <small class="text-primary">
                                                                <i class="fa fa-info-circle"></i> Dokumen wajib telah lengkap
                                                            </small>
                                                        @else
                                                            <small class="text-warning">
                                                                <i class="fa fa-exclamation-triangle"></i> Dokumen belum lengkap
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="badge badge-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $payment = $pendaftar->payments->first();
                                                @endphp
                                                @if ($payment)
                                                    @if ($payment->status === 'confirmed')
                                                        <span class="badge badge-success">Dikonfirmasi</span>
                                                    @elseif($payment->status === 'pending')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @else
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">Belum Bayar</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $pendaftar->created_at->format('d M Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('pendaftar.show', $pendaftar->id) }}" 
                                                       class="btn btn-link btn-primary btn-lg" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Lihat Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-link btn-success btn-edit-status" 
                                                            data-id="{{ $pendaftar->id }}" 
                                                            data-status="{{ $pendaftar->status }}"
                                                            data-status-bayar="{{ $payment->status ?? 'pending' }}"
                                                            data-bs-toggle="tooltip" 
                                                            title="Edit Status">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- DataTables-style Pagination -->
                        @if($pendaftars->hasPages())
                            <div class="row mt-4">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info" role="status" aria-live="polite">
                                        Menampilkan {{ $pendaftars->firstItem() }} sampai {{ $pendaftars->lastItem() }} dari {{ $pendaftars->total() }} data
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers">
                                        <ul class="pagination justify-content-end">
                                            {{-- Previous Button --}}
                                            @if ($pendaftars->onFirstPage())
                                                <li class="paginate_button page-item previous disabled">
                                                    <span class="page-link">Sebelumnya</span>
                                                </li>
                                            @else
                                                <li class="paginate_button page-item previous">
                                                    <a href="{{ $pendaftars->appends(request()->query())->previousPageUrl() }}" class="page-link">Sebelumnya</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($pendaftars->getUrlRange(1, $pendaftars->lastPage()) as $page => $url)
                                                @if ($page == $pendaftars->currentPage())
                                                    <li class="paginate_button page-item active">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="paginate_button page-item">
                                                        <a href="{{ $pendaftars->appends(request()->query())->url($page) }}" class="page-link">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($pendaftars->hasMorePages())
                                                <li class="paginate_button page-item next">
                                                    <a href="{{ $pendaftars->appends(request()->query())->nextPageUrl() }}" class="page-link">Selanjutnya</a>
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
                        <div class="alert alert-info">
                            <h4>Tidak Ada Data</h4>
                            <p>Tidak ada pendaftar dengan dokumen lengkap dan status "submitted" untuk periode yang dipilih.</p>
                            <hr>
                            <small>
                                <strong>Catatan:</strong> Halaman ini menampilkan pendaftar yang:
                                <ul class="mb-0">
                                    <li>Sudah melakukan pembayaran (confirmed)</li>
                                    <li>Memiliki status "submitted"</li>
                                    <li>Sudah melengkapi semua dokumen wajib</li>
                                </ul>
                                Pendaftar yang belum submit akan muncul di halaman "Dokumen Menunggu".
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status -->
    <div class="modal fade" id="modalStatus" tabindex="-1" aria-labelledby="modalStatusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formUbahStatus" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStatusLabel">Ubah Status Pendaftar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modalPendaftarId" name="pendaftar_id">
                        
                        <div class="mb-3">
                            <label for="statusPendaftaran" class="form-label">Status Dokumen</label>
                            <select class="form-select" id="statusPendaftaran" name="status" required>
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTable dengan fitur minimal - Laravel pagination yang handle
            $('#basic-datatables').DataTable({
                "paging": false,
                "searching": false,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 8] }
                ]
            });

            // Show loading overlay function
            function showLoading() {
                $('#loadingOverlay').css('display', 'flex');
            }

            // Auto submit filter when periode changes
            $('#periode_id').on('change', function() {
                showLoading();
                $('#filterForm').submit();
            });

            // Show loading on search submit
            $('#searchForm').on('submit', function() {
                showLoading();
            });

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

            // Event delegation untuk tombol edit yang ada di dalam DataTable
            $(document).on('click', '.btn-edit-status', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var button = $(this);
                var id = button.attr('data-id');
                var status = button.attr('data-status');
                var statusBayar = button.attr('data-status-bayar');
                
                if (!id) {
                    console.error('No ID found on button');
                    return;
                }
                
                // Set data ke modal
                document.getElementById('modalPendaftarId').value = id;
                document.getElementById('statusPendaftaran').value = status || 'draft';
                
                // Set action form
                var routeTemplate = "{{ route('pendaftar.update-status-dokumen', ['id' => ':id']) }}";
                var actionUrl = routeTemplate.replace(':id', id);
                document.getElementById('formUbahStatus').action = actionUrl;
                
                // Show modal
                var modalEl = document.getElementById('modalStatus');
                var modal = new bootstrap.Modal(modalEl);
                modal.show();
            });
        });
    </script>
@endpush