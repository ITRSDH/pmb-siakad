@extends('admin.layouts.app')

@section('title', 'Edit Biaya Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Biaya Pendaftaran</h3>
        <h6 class="op-7 mb-2">Perbarui informasi biaya pendaftaran</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('biaya-pendaftaran.show', $biayaPendaftaran) }}" class="btn btn-label-info btn-round me-2">
            <span class="btn-label"><i class="fa fa-eye"></i></span>
            Lihat Detail
        </a>
        <a href="{{ route('biaya-pendaftaran.index') }}" class="btn btn-label-secondary btn-round">
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
                    <h4 class="card-title">Form Edit Biaya Pendaftaran</h4>
                    <div class="ms-auto">
                        <span class="badge badge-warning">ID: {{ $biayaPendaftaran->id }}</span>
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

                <form action="{{ route('biaya-pendaftaran.update', $biayaPendaftaran) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_biaya">Nama Biaya <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_biaya') is-invalid @enderror" 
                                       id="nama_biaya" 
                                       name="nama_biaya" 
                                       value="{{ old('nama_biaya', $biayaPendaftaran->nama_biaya) }}"
                                       placeholder="Contoh: Biaya Pendaftaran, Biaya Tes Masuk"
                                       required>
                                @error('nama_biaya')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Masukkan nama biaya yang jelas dan deskriptif
                                </small>
                            </div>

                            <div class="form-group">
                                <label for="jumlah_biaya">Jumlah Biaya (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" 
                                           class="form-control @error('jumlah_biaya') is-invalid @enderror" 
                                           id="jumlah_biaya_display"
                                           placeholder="0"
                                           required>
                                    <!-- Hidden input untuk nilai asli -->
                                    <input type="hidden" 
                                           name="jumlah_biaya" 
                                           id="jumlah_biaya"
                                           value="{{ old('jumlah_biaya', $biayaPendaftaran->jumlah_biaya) }}">
                                </div>
                                @error('jumlah_biaya')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Nilai saat ini: <strong class="text-success">Rp {{ number_format($biayaPendaftaran->jumlah_biaya, 0, ',', '.') }}</strong>
                                </small>
                                <div id="preview_amount" class="mt-1 text-success fw-bold"></div>
                            </div>

                            <div class="form-group">
                                <label for="jalur_pendaftaran_id">Jalur Pendaftaran <span class="text-danger">*</span></label>
                                <select class="form-select @error('jalur_pendaftaran_id') is-invalid @enderror" 
                                        id="jalur_pendaftaran_id" 
                                        name="jalur_pendaftaran_id"
                                        required>
                                    <option value="">-- Pilih Jalur Pendaftaran --</option>
                                    @foreach($jalurPendaftarans as $jalur)
                                        <option value="{{ $jalur->id }}" {{ old('jalur_pendaftaran_id', $biayaPendaftaran->jalur_pendaftaran_id) == $jalur->id ? 'selected' : '' }}>
                                            {{ $jalur->nama_jalur }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jalur_pendaftaran_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Pilih jalur pendaftaran yang sesuai
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
                                        <small>{{ $biayaPendaftaran->created_at->format('d F Y, H:i') }}</small>
                                    </p>
                                    <p class="text-white mb-0">
                                        <strong>Terakhir diubah:</strong><br>
                                        <small>{{ $biayaPendaftaran->updated_at->format('d F Y, H:i') }}</small>
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
                        <a href="{{ route('biaya-pendaftaran.show', $biayaPendaftaran) }}" class="btn btn-danger">
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
<form id="deleteForm" action="{{ route('biaya-pendaftaran.destroy', $biayaPendaftaran) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const displayInput = document.getElementById('jumlah_biaya_display');
    const hiddenInput = document.getElementById('jumlah_biaya');
    const previewAmount = document.getElementById('preview_amount');
    
    // Form submission dengan loading modal
    $('form:not(#deleteForm)').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Tampilkan modal loading dengan animasi
        $('#loadingMessage').text('Memperbarui biaya pendaftaran...');
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
    
    // Format currency display
    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }
    
    // Set initial value from database
    if (hiddenInput.value) {
        displayInput.value = formatCurrency(hiddenInput.value);
        previewAmount.textContent = `Preview: Rp ${formatCurrency(hiddenInput.value)}`;
    }
    
    // Handle input formatting
    displayInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value === '') {
            hiddenInput.value = '';
            previewAmount.textContent = '';
            e.target.value = '';
            return;
        }
        
        hiddenInput.value = value;
        e.target.value = formatCurrency(value);
        previewAmount.textContent = `Preview: Rp ${formatCurrency(value)}`;
        
        // Move cursor to end
        setTimeout(() => {
            e.target.selectionStart = e.target.selectionEnd = e.target.value.length;
        }, 0);
    });
    
    // Handle paste event
    displayInput.addEventListener('paste', function(e) {
        e.preventDefault();
        let paste = (e.clipboardData || window.clipboardData).getData('text');
        let value = paste.replace(/\D/g, '');
        
        if (value !== '') {
            hiddenInput.value = value;
            e.target.value = formatCurrency(value);
            previewAmount.textContent = `Preview: Rp ${formatCurrency(value)}`;
        }
    });
});

function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus biaya pendaftaran ini? \n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.')) {
        // Tampilkan modal loading untuk delete
        $('#loadingMessage').text('Menghapus biaya pendaftaran...');
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
