@extends('admin.layouts.app')

@section('title', 'Tambah Jalur Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Tambah Jalur Pendaftaran</h3>
        <h6 class="op-7 mb-2">Tambahkan jalur pendaftaran mahasiswa baru</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('jalur-pendaftaran.index') }}" class="btn btn-label-info btn-round me-2">
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
                    <h4 class="card-title">Form Tambah Jalur Pendaftaran</h4>
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

                <form action="{{ route('jalur-pendaftaran.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_jalur">Nama Jalur Pendaftaran <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_jalur') is-invalid @enderror" 
                                       id="nama_jalur" 
                                       name="nama_jalur" 
                                       value="{{ old('nama_jalur') }}"
                                       placeholder="Masukkan nama jalur pendaftaran"
                                       required>
                                @error('nama_jalur')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Contoh: Jalur Prestasi, Jalur Reguler, Jalur Mandiri
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="5"
                                          placeholder="Masukkan deskripsi jalur pendaftaran (opsional)">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Jelaskan tentang jalur pendaftaran ini secara detail
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card card-info card-annoucement card-round">
                                <div class="card-body text-center">
                                    <div class="card-opening">Informasi</div>
                                    <div class="card-desc">
                                        <i class="fas fa-route fa-3x text-info mb-3"></i>
                                        <p>Pastikan nama jalur pendaftaran unik dan mudah diidentifikasi oleh calon mahasiswa.</p>
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
                        <a href="{{ route('jalur-pendaftaran.index') }}" class="btn btn-danger">
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
        $('#nama_jalur').focus();
    });
</script>
@endpush