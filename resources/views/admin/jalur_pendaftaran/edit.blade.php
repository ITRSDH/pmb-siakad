@extends('admin.layouts.app')

@section('title', 'Edit Jalur Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Jalur Pendaftaran</h3>
        <h6 class="op-7 mb-2">Perbarui informasi jalur pendaftaran</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('jalur-pendaftaran.show', $jalurPendaftaran) }}" class="btn btn-label-info btn-round me-2">
            <span class="btn-label"><i class="fa fa-eye"></i></span>
            Lihat Detail
        </a>
        <a href="{{ route('jalur-pendaftaran.index') }}" class="btn btn-label-secondary btn-round">
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
                    <h4 class="card-title">Form Edit Jalur Pendaftaran</h4>
                    <div class="ms-auto">
                        <span class="badge badge-warning">ID: {{ $jalurPendaftaran->id }}</span>
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

                <form action="{{ route('jalur-pendaftaran.update', $jalurPendaftaran) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_jalur">Nama Jalur Pendaftaran <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_jalur') is-invalid @enderror" 
                                       id="nama_jalur" 
                                       name="nama_jalur" 
                                       value="{{ old('nama_jalur', $jalurPendaftaran->nama_jalur) }}"
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
                                          placeholder="Masukkan deskripsi jalur pendaftaran (opsional)">{{ old('deskripsi', $jalurPendaftaran->deskripsi) }}</textarea>
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
                            <div class="card card-primary card-round">
                                <div class="card-body">
                                    <h5 class="card-title text-white"><i class="fas fa-info-circle"></i> Informasi Data</h5>
                                    <div class="separator-solid"></div>
                                    <p class="text-white mb-2">
                                        <strong>Dibuat:</strong><br>
                                        <small>{{ $jalurPendaftaran->created_at->format('d F Y, H:i') }}</small>
                                    </p>
                                    <p class="text-white mb-0">
                                        <strong>Terakhir diubah:</strong><br>
                                        <small>{{ $jalurPendaftaran->updated_at->format('d F Y, H:i') }}</small>
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
                        <a href="{{ route('jalur-pendaftaran.show', $jalurPendaftaran) }}" class="btn btn-danger">
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
<form id="deleteForm" action="{{ route('jalur-pendaftaran.destroy', $jalurPendaftaran) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto focus on first input
        $('#nama_jalur').focus();
        
        // Form submission dengan loading modal
        $('form:not(#deleteForm)').on('submit', function(e) {
            const submitBtn = $(this).find('button[type="submit"]');
            
            // Tampilkan modal loading dengan animasi
            $('#loadingMessage').text('Memperbarui jalur pendaftaran...');
            $('#loadingModal').modal('show');
            
            // Update tombol submit
            submitBtn.prop('disabled', true);
            submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memperbarui...');
            
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

    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus jalur pendaftaran "{{ $jalurPendaftaran->nama_jalur }}"? \n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.')) {
            // Tampilkan modal loading untuk delete
            $('#loadingMessage').text('Menghapus jalur pendaftaran...');
            $('#loadingModal').modal('show');
            
            // Update delete button
            $('button[onclick="confirmDelete()"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');
            
            // Submit form delete dengan delay
            setTimeout(() => {
                document.getElementById('deleteForm').submit();
            }, 300);
        }
    }
</script>
@endpush