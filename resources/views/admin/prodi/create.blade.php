@extends('admin.layouts.app')

@section('title', 'Tambah Program Studi')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Tambah Program Studi</h3>
        <h6 class="op-7 mb-2">Tambahkan program studi baru</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('prodi.index') }}" class="btn btn-label-info btn-round me-2">
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
                    <h4 class="card-title">Form Tambah Program Studi</h4>
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

                <form action="{{ route('prodi.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_prodi">Nama Program Studi <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_prodi') is-invalid @enderror" 
                                       id="nama_prodi" 
                                       name="nama_prodi" 
                                       value="{{ old('nama_prodi') }}"
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
                                       value="{{ old('kode_prodi') }}"
                                       placeholder="Contoh: TI, SI, TE"
                                       required>
                                @error('kode_prodi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Kode singkat untuk identifikasi program studi
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Program Studi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="4"
                                          placeholder="Deskripsi singkat mengenai program studi...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Deskripsi opsional tentang program studi (opsional)
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card card-primary card-annoucement card-round">
                                <div class="card-body text-center">
                                    <div class="card-opening text-white">Informasi</div>
                                    <div class="card-desc">
                                        <i class="fas fa-university fa-3x text-white mb-3"></i>
                                        <p class="text-white">Pastikan kode program studi unik dan mudah diingat.</p>
                                    </div>
                                    <div class="card-detail">
                                        <div class="btn btn-primary btn-round btn-sm">Tips Form</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">
                            <span class="btn-label"><i class="fa fa-check"></i></span>
                            Simpan Data
                        </button>
                        <a href="{{ route('prodi.index') }}" class="btn btn-danger">
                            <span class="btn-label"><i class="fa fa-times"></i></span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto focus on first input
    $('#nama_prodi').focus();
    
    // Auto generate kode prodi dari nama prodi
    $('#nama_prodi').on('input', function() {
        let nama = $(this).val();
        let kode = nama.split(' ').map(word => word.charAt(0).toUpperCase()).join('');
        if (kode.length > 0 && $('#kode_prodi').val() === '') {
            $('#kode_prodi').val(kode);
        }
    });
});
</script>
@endpush
