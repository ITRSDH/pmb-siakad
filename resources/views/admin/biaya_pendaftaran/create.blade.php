@extends('admin.layouts.app')

@section('title', 'Tambah Biaya Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Tambah Biaya Pendaftaran</h3>
        <h6 class="op-7 mb-2">Tambahkan biaya pendaftaran mahasiswa baru</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('biaya-pendaftaran.index') }}" class="btn btn-label-info btn-round me-2">
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
                    <h4 class="card-title">Form Tambah Biaya Pendaftaran</h4>
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

                <form action="{{ route('biaya-pendaftaran.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nama_biaya">Nama Biaya <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_biaya') is-invalid @enderror" 
                                       id="nama_biaya" 
                                       name="nama_biaya" 
                                       value="{{ old('nama_biaya') }}"
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
                                           value="{{ old('jumlah_biaya') }}">
                                </div>
                                @error('jumlah_biaya')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Masukkan nominal biaya dalam rupiah
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
                                        <option value="{{ $jalur->id }}" {{ old('jalur_pendaftaran_id') == $jalur->id ? 'selected' : '' }}>
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
                            <div class="card card-success card-annoucement card-round">
                                <div class="card-body text-center">
                                    <div class="card-opening text-white">Informasi</div>
                                    <div class="card-desc">
                                        <i class="fas fa-money-bill-wave fa-3x text-white mb-3"></i>
                                        <p class="text-white">Pastikan kombinasi nama biaya dan jalur pendaftaran tidak duplikasi.</p>
                                    </div>
                                    <div class="card-detail">
                                        <div class="btn btn-success btn-round btn-sm">Tips Form</div>
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
                        <a href="{{ route('biaya-pendaftaran.index') }}" class="btn btn-danger">
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
    const displayInput = document.getElementById('jumlah_biaya_display');
    const hiddenInput = document.getElementById('jumlah_biaya');
    const previewAmount = document.getElementById('preview_amount');
    
    // Auto focus on first input
    $('#nama_biaya').focus();
    
    // Form submission dengan loading modal
    $('form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Tampilkan modal loading dengan animasi
        $('#loadingMessage').text('Menyimpan biaya pendaftaran...');
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
    
    // Format angka dengan thousand separator
    function formatNumber(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    }
    
    // Hapus semua karakter non-digit
    function unformatNumber(value) {
        return value.replace(/\D/g, '');
    }
    
    // Set nilai awal jika ada old value
    if (hiddenInput.value) {
        displayInput.value = formatNumber(hiddenInput.value);
        updatePreview(hiddenInput.value);
    }
    
    // Event listener untuk input display
    displayInput.addEventListener('input', function(e) {
        let value = unformatNumber(e.target.value);
        
        // Update hidden input dengan nilai asli
        hiddenInput.value = value;
        
        // Format display input
        if (value) {
            e.target.value = formatNumber(value);
            updatePreview(value);
        } else {
            e.target.value = '';
            previewAmount.textContent = '';
        }
    });
    
    // Update preview text
    function updatePreview(value) {
        if (value && value > 0) {
            const formatted = formatNumber(value);
            previewAmount.innerHTML = '<i class="fas fa-money-bill-wave me-1"></i>Rp ' + formatted;
        } else {
            previewAmount.textContent = '';
        }
    }
    
    // Validasi hanya angka
    displayInput.addEventListener('keypress', function(e) {
        // Hanya izinkan angka dan beberapa karakter khusus
        if (!/[\d.]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush