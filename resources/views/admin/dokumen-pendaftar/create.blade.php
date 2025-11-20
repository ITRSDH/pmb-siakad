@extends('admin.layouts.app')

@section('title', 'Tambah Dokumen Pendaftar')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Tambah Dokumen Pendaftar</h3>
        <h6 class="op-7 mb-2">Tambahkan dokumen baru untuk pendaftaran mahasiswa</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('dokumen-pendaftar.index') }}" class="btn btn-label-info btn-round me-2">
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
                    <h4 class="card-title">Form Tambah Dokumen Pendaftar</h4>
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

                <form action="{{ route('dokumen-pendaftar.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_dokumen">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_dokumen') is-invalid @enderror" 
                                       id="nama_dokumen" 
                                       name="nama_dokumen" 
                                       value="{{ old('nama_dokumen') }}"
                                       placeholder="Contoh: KTP, Ijazah, Transkrip Nilai"
                                       required>
                                @error('nama_dokumen')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Masukkan nama dokumen yang diperlukan untuk pendaftaran
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card card-primary card-annoucement card-round">
                                <div class="card-body text-center">
                                    <div class="card-opening text-white">Informasi</div>
                                    <div class="card-desc">
                                        <i class="fas fa-file-alt fa-3x text-white mb-3"></i>
                                        <p class="text-white">Dokumen wajib harus diupload oleh pendaftar sebelum menyelesaikan pendaftaran.</p>
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
                        <a href="{{ route('dokumen-pendaftar.index') }}" class="btn btn-danger">
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
    $('#nama_dokumen').focus();
    
    // Form submission dengan loading modal
    $('form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Tampilkan modal loading dengan animasi
        $('#loadingMessage').text('Menyimpan dokumen pendaftar...');
        $('#loadingModal').modal('show');
        
        // Update tombol submit
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
        
        // Tambahkan sedikit delay untuk user experience
        setTimeout(() => {
            // Form akan submit secara otomatis setelah delay
        }, 100);
        
        // Biarkan form submit secara normal
        return true;
    });
    
    // Hide loading jika ada error (page reload)
    $(window).on('load', function() {
        $('#loadingModal').modal('hide');
    });
});
</script>
@endpush