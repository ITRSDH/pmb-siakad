@extends('admin.layouts.app')

@section('title', 'Edit Program Studi')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Program Studi</h3>
        <h6 class="op-7 mb-2">Perbarui informasi program studi</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('prodi.show', $prodi) }}" class="btn btn-label-info btn-round me-2">
            <span class="btn-label"><i class="fa fa-eye"></i></span>
            Lihat Detail
        </a>
        <a href="{{ route('prodi.index') }}" class="btn btn-label-secondary btn-round">
            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Form Edit Program Studi</h4>
                    <div class="ms-auto">
                        <span class="badge badge-warning">ID: {{ $prodi->id }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Ups! Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('prodi.update', $prodi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_prodi">Nama Program Studi <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_prodi') is-invalid @enderror" 
                                       id="nama_prodi" 
                                       name="nama_prodi" 
                                       value="{{ old('nama_prodi', $prodi->nama_prodi) }}"
                                       placeholder="Contoh: Teknik Informatika, Sistem Informasi"
                                       required>
                                @error('nama_prodi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Masukkan nama program studi yang lengkap
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="kode_prodi">Kode Program Studi <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('kode_prodi') is-invalid @enderror" 
                                       id="kode_prodi" 
                                       name="kode_prodi" 
                                       value="{{ old('kode_prodi', $prodi->kode_prodi) }}"
                                       placeholder="Contoh: TI, SI, TE"
                                       required>
                                @error('kode_prodi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Kode saat ini: <strong class="text-success">{{ $prodi->kode_prodi }}</strong>
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Program Studi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="4"
                                          placeholder="Deskripsi singkat mengenai program studi...">{{ old('deskripsi', $prodi->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Deskripsi opsional tentang program studi
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card card-primary card-round">
                                <div class="card-body">
                                    <h5 class="card-title text-white"><i class="fas fa-info-circle"></i> Informasi Data</h5>
                                    <div class="separator-solid"></div>
                                    <p class="text-white mb-2">
                                        <strong>Dibuat:</strong><br>
                                        <small>{{ $prodi->created_at->format('d F Y, H:i') }}</small>
                                    </p>
                                    <p class="text-white mb-0">
                                        <strong>Terakhir diubah:</strong><br>
                                        <small>{{ $prodi->updated_at->format('d F Y, H:i') }}</small>
                                    </p>
                                </div>
                            </div>

                            <div class="card card-warning card-round">
                                <div class="card-body text-center">
                                    <div class="card-opening text-white">Zona Bahaya</div>
                                    <div class="card-desc">
                                        <p class="text-white">Hapus data ini secara permanen</p>
                                    </div>
                                    <div class="card-detail">
                                        <button type="button" 
                                                class="btn btn-danger btn-round btn-sm"
                                                onclick="confirmDelete()">
                                            <i class="fa fa-trash"></i> Hapus Data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">
                            <span class="btn-label"><i class="fa fa-check"></i></span>
                            Update Data
                        </button>
                        <a href="{{ route('prodi.show', $prodi) }}" class="btn btn-danger">
                            <span class="btn-label"><i class="fa fa-times"></i></span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Form for Delete -->
<form id="deleteForm" action="{{ route('prodi.destroy', $prodi) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus program studi "' + '{{ $prodi->nama_prodi }}' + '"? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('deleteForm').submit();
    }
}

$(document).ready(function() {
    // Auto focus on first input
    $('#nama_prodi').focus();
});
</script>
@endpush
