@extends('admin.layouts.app')

@section('title', 'Tambah Periode Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Tambah Periode Pendaftaran</h3>
        <h6 class="op-7 mb-2">Buat periode pendaftaran baru untuk mahasiswa</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('periode-pendaftaran.index') }}" class="btn btn-label-secondary btn-round">
            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">
                        <i class="fas fa-calendar-plus text-primary me-2"></i>
                        Form Tambah Periode Pendaftaran
                    </h4>
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

                <form action="{{ route('periode-pendaftaran.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama_periode">Nama Periode <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_periode') is-invalid @enderror" 
                               id="nama_periode" 
                               name="nama_periode" 
                               value="{{ old('nama_periode') }}"
                               placeholder="Contoh: Periode 1 Gelombang 1 Reguler"
                               required>
                        @error('nama_periode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Masukkan nama periode yang deskriptif dan mudah dipahami
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3"
                                  placeholder="Deskripsi tambahan untuk periode ini (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gelombang_id">Gelombang <span class="text-danger">*</span></label>
                                <select class="form-select @error('gelombang_id') is-invalid @enderror" 
                                        id="gelombang_id" 
                                        name="gelombang_id"
                                        required>
                                    <option value="">-- Pilih Gelombang --</option>
                                    @foreach($gelombangs as $gelombang)
                                        <option value="{{ $gelombang->id }}" {{ old('gelombang_id') == $gelombang->id ? 'selected' : '' }}>
                                            {{ $gelombang->nama_gelombang }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gelombang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="biaya_pendaftaran_id">Biaya Pendaftaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('biaya_pendaftaran_id') is-invalid @enderror" 
                                id="biaya_pendaftaran_id" 
                                name="biaya_pendaftaran_id"
                                required>
                            <option value="">-- Pilih jalur pendaftaran terlebih dahulu --</option>
                        </select>
                        @error('biaya_pendaftaran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Biaya akan dimuat otomatis sesuai jalur pendaftaran yang dipilih
                        </small>
                        <div id="biaya_info" class="mt-2" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Biaya Pendaftaran:</strong> <span id="biaya_amount" class="fw-bold"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" 
                                       name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('tanggal_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" 
                                       name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai') }}"
                                       required>
                                @error('tanggal_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kuota">Kuota Pendaftar <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('kuota') is-invalid @enderror" 
                                       id="kuota" 
                                       name="kuota" 
                                       value="{{ old('kuota', 100) }}"
                                       min="1"
                                       max="10000"
                                       required>
                                @error('kuota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Maksimal 10.000 pendaftar per periode
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status"
                                        required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <strong>Draft:</strong> Masih dalam proses penyusunan<br>
                                    <strong>Aktif:</strong> Siap menerima pendaftar
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">
                            <span class="btn-label"><i class="fa fa-check"></i></span>
                            Simpan Periode Pendaftaran
                        </button>
                        <a href="{{ route('periode-pendaftaran.index') }}" class="btn btn-danger">
                            <span class="btn-label"><i class="fa fa-times"></i></span>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-primary card-round">
            <div class="card-body">
                <h5 class="card-title text-white">
                    <i class="fas fa-info-circle"></i> Informasi
                </h5>
                <div class="separator-solid"></div>
                <p class="text-white">
                    <strong>Periode Pendaftaran</strong> adalah kombinasi dari gelombang, jalur pendaftaran, dan biaya yang dibuat untuk mengatur pendaftaran mahasiswa baru.
                </p>
                <ul class="text-white">
                    <li>Pilih gelombang terlebih dahulu</li>
                    <li>Pilih jalur pendaftaran</li>
                    <li>Biaya akan otomatis dimuat sesuai jalur</li>
                    <li>Tentukan periode tanggal yang tepat</li>
                    <li>Atur kuota sesuai kapasitas</li>
                </ul>
            </div>
        </div>

        <div class="card card-warning card-round">
            <div class="card-body text-center">
                <div class="card-opening text-white">Tips</div>
                <div class="card-desc">
                    <p class="text-white">Pastikan tidak ada periode yang bentrok untuk gelombang dan jalur yang sama</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jalurSelect = document.getElementById('jalur_pendaftaran_id');
    const biayaSelect = document.getElementById('biaya_pendaftaran_id');
    const biayaInfo = document.getElementById('biaya_info');
    const biayaAmount = document.getElementById('biaya_amount');
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');

    // AJAX untuk load biaya berdasarkan jalur pendaftaran
    jalurSelect.addEventListener('change', function() {
        const jalurId = this.value;
        
        // Reset biaya select
        biayaSelect.innerHTML = '<option value="">-- Loading... --</option>';
        biayaInfo.style.display = 'none';
        
        if (!jalurId) {
            biayaSelect.innerHTML = '<option value="">-- Pilih jalur pendaftaran terlebih dahulu --</option>';
            return;
        }

        // Fetch biaya berdasarkan jalur
        fetch(`{{ route('ajax.biaya-by-jalur') }}?jalur_id=${jalurId}`)
            .then(response => response.json())
            .then(data => {
                biayaSelect.innerHTML = '<option value="">-- Pilih Biaya Pendaftaran --</option>';
                
                data.forEach(biaya => {
                    const option = document.createElement('option');
                    option.value = biaya.id;
                    option.textContent = `${biaya.nama_biaya} - Rp ${new Intl.NumberFormat('id-ID').format(biaya.jumlah_biaya)}`;
                    option.dataset.amount = biaya.jumlah_biaya;
                    biayaSelect.appendChild(option);
                });

                // Restore selected value if exists (untuk old input)
                const oldBiayaId = '{{ old('biaya_pendaftaran_id') }}';
                if (oldBiayaId) {
                    biayaSelect.value = oldBiayaId;
                    biayaSelect.dispatchEvent(new Event('change'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                biayaSelect.innerHTML = '<option value="">-- Error memuat data --</option>';
            });
    });

    // Show biaya info when biaya selected
    biayaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.dataset.amount) {
            const amount = new Intl.NumberFormat('id-ID').format(selectedOption.dataset.amount);
            biayaAmount.textContent = `Rp ${amount}`;
            biayaInfo.style.display = 'block';
        } else {
            biayaInfo.style.display = 'none';
        }
    });

    // Validasi tanggal
    tanggalMulai.addEventListener('change', function() {
        tanggalSelesai.min = this.value;
        
        if (tanggalSelesai.value && tanggalSelesai.value < this.value) {
            tanggalSelesai.value = this.value;
        }
    });

    // Load biaya jika ada old jalur_pendaftaran_id
    if (jalurSelect.value) {
        jalurSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush