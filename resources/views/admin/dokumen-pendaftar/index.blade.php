@extends('admin.layouts.app')

@section('title', 'Data Dokumen Pendaftar')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Dokumen Pendaftar</h3>
        <h6 class="op-7 mb-2">Kelola jenis dokumen yang harus dilengkapi pendaftar</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('dokumen-pendaftar.create') }}" class="btn btn-primary btn-round">
            <span class="btn-label"><i class="fa fa-plus"></i></span>
            Tambah Dokumen
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
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
                    <div class="card-title">Data Dokumen Pendaftar</div>
                    <div class="card-tools">
                        <div class="dropdown">
                            <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('dokumen-pendaftar.create') }}">Tambah Data</a>
                                <a class="dropdown-item" href="#" onclick="window.print()">Cetak Data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($dokumenPendaftars->count() > 0)
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Dokumen</th>
                                    <th>Tanggal Dibuat</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumenPendaftars as $index => $dokumen)
                                    <tr>
                                        <td>{{ $dokumenPendaftars->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $dokumen->nama_dokumen }}</strong>
                                        </td>
                                        <td>{{ $dokumen->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="{{ route('dokumen-pendaftar.show', $dokumen) }}" 
                                                   class="btn btn-link btn-info btn-lg" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Lihat Detail">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('dokumen-pendaftar.edit', $dokumen) }}" 
                                                   class="btn btn-link btn-primary btn-lg" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit Data">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-link btn-danger btn-lg" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Hapus Data"
                                                        onclick="confirmDelete('{{ route('dokumen-pendaftar.destroy', $dokumen) }}', '{{ $dokumen->nama_dokumen }}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $dokumenPendaftars->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-5x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum ada dokumen pendaftar</h4>
                        <p class="text-muted mb-4">Mulai dengan menambahkan dokumen pendaftar untuk sistem PMB.</p>
                        <a href="{{ route('dokumen-pendaftar.create') }}" class="btn btn-primary btn-round">
                            <span class="btn-label"><i class="fa fa-plus"></i></span>
                            Tambah Dokumen
                        </a>
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
    function confirmDelete(url, name) {
        if (confirm('Apakah Anda yakin ingin menghapus dokumen "' + name + '"? Tindakan ini tidak dapat dibatalkan.')) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }

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
    });
</script>
@endpush