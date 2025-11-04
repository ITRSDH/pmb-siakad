@extends('admin.layouts.app')

@section('title', 'Daftar Gelombang')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Gelombang Pendaftaran</h3>
        <h6 class="op-7 mb-2">Kelola gelombang pendaftaran mahasiswa baru</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('gelombang.create') }}" class="btn btn-primary btn-round">
            <span class="btn-label"><i class="fa fa-plus"></i></span>
            Tambah Gelombang
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row card-tools-still-right">
                    <div class="card-title">Data Gelombang Pendaftaran</div>
                    <div class="card-tools">
                        <div class="dropdown">
                            <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('gelombang.create') }}">Tambah Data</a>
                                <a class="dropdown-item" href="#" onclick="window.print()">Cetak Data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($gelombangs->count() > 0)
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Gelombang</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Dibuat</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gelombangs as $index => $gelombang)
                                    <tr>
                                        <td>{{ $gelombangs->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $gelombang->nama_gelombang }}</strong>
                                        </td>
                                        <td>
                                            {{ Str::limit($gelombang->deskripsi ?? 'Tidak ada deskripsi', 80) }}
                                        </td>
                                        <td>{{ $gelombang->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="{{ route('gelombang.show', $gelombang) }}" 
                                                   class="btn btn-link btn-info btn-lg" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Lihat Detail">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('gelombang.edit', $gelombang) }}" 
                                                   class="btn btn-link btn-primary btn-lg" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Edit Data">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-link btn-danger btn-lg" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Hapus Data"
                                                        onclick="confirmDelete('{{ route('gelombang.destroy', $gelombang) }}', '{{ $gelombang->nama_gelombang }}')">
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
                        {{ $gelombangs->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-water fa-5x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">Belum ada data gelombang</h4>
                        <p class="text-muted mb-4">Mulai dengan menambahkan gelombang pendaftaran pertama untuk sistem PMB.</p>
                        <a href="{{ route('gelombang.create') }}" class="btn btn-primary btn-round">
                            <span class="btn-label"><i class="fa fa-plus"></i></span>
                            Tambah Gelombang
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
        if (confirm('Apakah Anda yakin ingin menghapus gelombang "' + name + '"? Tindakan ini tidak dapat dibatalkan.')) {
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