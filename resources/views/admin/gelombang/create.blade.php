@extends('admin.layouts.app')

@section('title', 'Tambah Gelombang')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Tambah Gelombang Pendaftaran</h3>
        <h6 class="op-7 mb-2">Tambahkan gelombang pendaftaran mahasiswa baru</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('gelombang.index') }}" class="btn btn-label-info btn-round me-2">
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
                    <h4 class="card-title">Form Tambah Gelombang</h4>
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

                <form action="{{ route('gelombang.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_gelombang">Nama Gelombang <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_gelombang') is-invalid @enderror" 
                                       id="nama_gelombang" 
                                       name="nama_gelombang" 
                                       value="{{ old('nama_gelombang') }}"
                                       placeholder="Masukkan nama gelombang"
                                       required>
                                @error('nama_gelombang')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Contoh: Gelombang 1, Gelombang 2, Gelombang Khusus
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="5"
                                          placeholder="Masukkan deskripsi gelombang (opsional)">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jelaskan tentang gelombang pendaftaran ini secara detail
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card card-info card-annoucement card-round">
                                <div class="card-body text-center">
                                    <div class="card-opening text-white">Informasi</div>
                                    <div class="card-desc">
                                        <i class="fas fa-water fa-3x text-white mb-3"></i>
                                        <p class="text-white">Pastikan nama gelombang unik dan mudah diidentifikasi sesuai periode pendaftaran.</p>
                                    </div>
                                    <div class="card-detail">
                                        <div class="btn btn-info btn-round btn-sm">Tips Form</div>
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
                        <a href="{{ route('gelombang.index') }}" class="btn btn-danger">
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
    // Auto focus on first input
    $(document).ready(function() {
        $('#nama_gelombang').focus();
    });
</script>
@endpush