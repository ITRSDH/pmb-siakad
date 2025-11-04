@extends('admin.layouts.app')

@section('title', 'Data Semua Pendaftar')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Data Pendaftar Menunggu</h3>
            <h6 class="op-7 mb-2">Kelola data pendaftar mahasiswa baru</h6>
        </div>
        {{-- <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('biaya-pendaftaran.create') }}" class="btn btn-primary btn-round">
            <span class="btn-label"><i class="fa fa-plus"></i></span>
            Tambah Pendaftar
        </a>
    </div> --}}
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
                        <div class="card-title">Data Pendaftar Menunggu</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    {{-- <a class="dropdown-item" href="{{ route('biaya-pendaftaran.create') }}">Tambah Data</a> --}}
                                    <a class="dropdown-item" href="#" onclick="window.print()">Cetak Data</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('pendaftar.index') }}" class="d-flex align-items-end">
                                <div class="me-2" style="min-width: 250px;">
                                    <label for="periode_id" class="form-label">Filter Periode Pendaftaran</label>
                                    <select name="periode_id" id="periode_id" class="form-select">
                                        <option value="">-- Semua Periode --</option>
                                        @foreach($periodes as $periode)
                                            <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                                {{ $periode->nama_periode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="me-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                                @if(request('periode_id'))
                                    <div>
                                        <a href="{{ route('pendaftar.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                Total: <strong>{{ $pendaftars->count() }}</strong> pendaftar
                                @if(request('periode_id'))
                                    dalam periode yang dipilih
                                @endif
                            </small>
                        </div>
                    </div>

                    @if ($pendaftars->count() > 0)
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nomor Pendaftaran</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Periode</th>
                                        <th>Jalur</th>
                                        <th>Status</th>
                                        <th>Status Pembayaran</th>
                                        <th>Tanggal Daftar</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendaftars as $p)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $p->nomor_pendaftaran }}</strong></td>
                                            <td>{{ $p->nama_lengkap }}</td>
                                            <td>{{ $p->email }}</td>
                                            <td>{{ optional($p->periodePendaftaran)->nama_periode ?? '-' }}</td>
                                            <td>{{ optional(optional($p->periodePendaftaran)->jalurPendaftaran)->nama_jalur ?? '-' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $p->status == 'verified' ? 'success' : ($p->status == 'submitted' ? 'info' : ($p->status == 'draft' ? 'warning' : 'danger')) }}">
                                                    {{ ucfirst($p->status) }}
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
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('pendaftar.show', $p) }}"
                                                        class="btn btn-link btn-info btn-lg" data-bs-toggle="tooltip"
                                                        title="Lihat Detail">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-link btn-success btn-lg btn-edit-status"
                                                        data-bs-toggle="modal" data-bs-target="#modalStatus"
                                                        data-id="{{ $p->id }}" data-status="{{ $p->status }}"
                                                        data-status-bayar="{{ $latest?->status ?? '' }}"
                                                        title="Ubah Status">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination by DataTables only -->
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-user-graduate fa-5x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">Belum ada data pendaftar</h4>
                            <p class="text-muted mb-4">Belum ada mahasiswa yang mendaftar.</p>
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
                        <h5 class="modal-title" id="modalStatusLabel">Ubah Status
                            Pendaftar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="modalPendaftarId">
                        <div class="mb-3">
                            <label for="statusPendaftaran" class="form-label">Status
                                Pendaftaran</label>
                            <select class="form-select" id="statusPendaftaran" name="status">
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="statusPembayaran" class="form-label">Status
                                Pembayaran</label>
                            <select class="form-select" id="statusPembayaran" name="status_pembayaran">
                                <option value="pending">Menunggu</option>
                                <option value="confirmed">Lunas</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({
                "pageLength": 10,
                "searching": true,
                "paging": true,
                "ordering": true,
                "info": true,
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

            // Auto submit when periode filter changes
            $('#periode_id').on('change', function() {
                $(this).closest('form').submit();
            });

            // Event delegation untuk tombol edit yang ada di dalam DataTable
            $(document).on('click', '.btn-edit-status', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('Button clicked!'); // Debug log
                
                var button = $(this);
                var id = button.attr('data-id');
                var status = button.attr('data-status');
                var statusBayar = button.attr('data-status-bayar');
                
                console.log('Button data:', {
                    id: id, 
                    status: status, 
                    statusBayar: statusBayar
                });
                
                if (!id) {
                    console.error('No ID found on button');
                    return;
                }
                
                // Set data ke modal
                document.getElementById('modalPendaftarId').value = id;
                document.getElementById('statusPendaftaran').value = status || 'draft';
                document.getElementById('statusPembayaran').value = statusBayar || 'pending';
                
                // Set action form
                var routeTemplate = "{{ route('pendaftar.update-status', ['id' => ':id']) }}";
                var actionUrl = routeTemplate.replace(':id', id);
                document.getElementById('formUbahStatus').action = actionUrl;
                
                console.log('Form action set to:', actionUrl);
                
                // Show modal using Bootstrap 5 syntax
                try {
                    var modalEl = document.getElementById('modalStatus');
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    console.log('Modal should be shown');
                } catch (error) {
                    console.error('Error showing modal:', error);
                    // Fallback: try jQuery
                    try {
                        $('#modalStatus').modal('show');
                        console.log('Modal shown via jQuery');
                    } catch (jqError) {
                        console.error('jQuery modal error:', jqError);
                    }
                }
            });

            // Modal: isi data saat tombol edit diklik (fallback method)
            $('#modalStatus').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                if (button.length) {
                    var id = button.data('id');
                    var status = button.data('status');
                    var statusBayar = button.data('status-bayar');
                    var modal = $(this);
                    modal.find('#modalPendaftarId').val(id);
                    modal.find('#statusPendaftaran').val(status);
                    modal.find('#statusPembayaran').val(statusBayar);
                    // Set action form pakai route alias dari Laravel (dari Blade)
                    var routeTemplate = "{{ route('pendaftar.update-status', ['id' => ':id']) }}";
                    var actionUrl = routeTemplate.replace(':id', id);
                    modal.find('#formUbahStatus').attr('action', actionUrl);
                }
            });
        });
    </script>
@endpush
