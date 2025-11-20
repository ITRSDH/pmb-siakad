@extends('admin.layouts.app')

@section('title', 'Edit Gelombang')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Gelombang Pendaftaran</h3>
        <h6 class="op-7 mb-2">Perbarui informasi gelombang pendaftaran</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('gelombang.show', $gelombang) }}" class="btn btn-label-info btn-round me-2">
            <span class="btn-label"><i class="fa fa-eye"></i></span>
            Lihat Detail
        </a>
        <a href="{{ route('gelombang.index') }}" class="btn btn-label-secondary btn-round">
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
                    <h4 class="card-title">Form Edit Gelombang</h4>
                    <div class="ms-auto">
                        <span class="badge badge-warning">ID: {{ $gelombang->id }}</span>
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

                <form action="{{ route('gelombang.update', $gelombang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_gelombang">Nama Gelombang <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_gelombang') is-invalid @enderror" 
                                       id="nama_gelombang" 
                                       name="nama_gelombang" 
                                       value="{{ old('nama_gelombang', $gelombang->nama_gelombang) }}"
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
                                          placeholder="Masukkan deskripsi gelombang (opsional)">{{ old('deskripsi', $gelombang->deskripsi) }}</textarea>
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
                            <div class="card card-primary card-round">
                                <div class="card-body">
                                    <h5 class="card-title text-white"><i class="fas fa-info-circle"></i> Informasi Data</h5>
                                    <div class="separator-solid"></div>
                                    <p class="text-white mb-2">
                                        <strong>Dibuat:</strong><br>
                                        <small>{{ $gelombang->created_at->format('d F Y, H:i') }}</small>
                                    </p>
                                    <p class="text-white mb-0">
                                        <strong>Terakhir diubah:</strong><br>
                                        <small>{{ $gelombang->updated_at->format('d F Y, H:i') }}</small>
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
                        <a href="{{ route('gelombang.show', $gelombang) }}" class="btn btn-danger">
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
<form id="deleteForm" action="{{ route('gelombang.destroy', $gelombang) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto focus on first input
        $('#nama_gelombang').focus();
        
        // Form submission dengan loading modal
        $('form:not(#deleteForm)').on('submit', function(e) {
            const submitBtn = $(this).find('button[type="submit"]');
            
            // Tampilkan modal loading dengan animasi
            $('#loadingMessage').text('Memperbarui gelombang pendaftaran...');
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
        if (confirm('Apakah Anda yakin ingin menghapus gelombang "{{ $gelombang->nama_gelombang }}"? \n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.')) {
            // Tampilkan modal loading untuk delete
            $('#loadingMessage').text('Menghapus gelombang pendaftaran...');
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