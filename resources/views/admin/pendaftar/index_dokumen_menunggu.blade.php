@extends('admin.layouts.app')

@section('title', 'Pendaftar Dokumen Menunggu')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Data Pendaftar Dokumen Menunggu</h3>
            <h6 class="op-7 mb-2">Kelola data pendaftar yang belum melengkapi dokumen</h6>
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
                        <div class="card-title">Daftar Pendaftar Dokumen Menunggu</div>
                        <div class="card-tools">
                            <span class="badge badge-warning">{{ $pendaftars->count() }} Pendaftar</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('pendaftar.dokumen.menunggu') }}">
                                <div class="form-group">
                                    <label for="periode_id">Filter Periode:</label>
                                    <select name="periode_id" id="periode_id" class="form-select form-control">
                                        <option value="">-- Semua Periode --</option>
                                        @foreach ($periodes as $periode)
                                            <option value="{{ $periode->id }}" {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                                {{ $periode->nama_periode }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
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
                                        <th>Status Dokumen</th>
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
                                                        <div class="progress mb-1" style="height: 8px;">
                                                            <div class="progress-bar bg-warning" role="progressbar" 
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
                                                    </div>
                                                @else
                                                    <span class="badge badge-secondary">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($pendaftar->status))
                                                    @if ($pendaftar->status === 'draft')
                                                        <span class="badge badge-secondary">Menunggu Persetujuan</span>
                                                    @elseif ($pendaftar->status === 'submitted')
                                                        <span class="badge badge-primary">Diterima</span>
                                                    @elseif ($pendaftar->status === 'rejected')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge badge-light">Belum Diajukan</span>
                                                    @endif
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
                                                            class="btn btn-link btn-warning btn-edit-status" 
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
                    @else
                        <div class="alert alert-info">
                            <h4>Tidak Ada Data</h4>
                            <p>Tidak ada pendaftar dengan status selain "submitted" untuk periode yang dipilih.</p>
                            <hr>
                            <small>
                                <strong>Catatan:</strong> Halaman ini menampilkan pendaftar yang:
                                <ul class="mb-0">
                                    <li>Sudah melakukan pembayaran (confirmed)</li>
                                    <li>Memiliki status selain "submitted" (draft/rejected)</li>
                                    <li>Baik yang sudah lengkap maupun belum lengkap dokumennya</li>
                                </ul>
                                Pendaftar dengan status "submitted" akan muncul di halaman "Dokumen Diterima".
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