@extends('admin.layouts.app')

@section('title', 'Edit Periode Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Edit Periode Pendaftaran</h3>
        <h6 class="op-7 mb-2">Perbarui informasi periode pendaftaran</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('periode-pendaftaran.show', $periodePendaftaran) }}" class="btn btn-label-info btn-round me-2">
            <span class="btn-label"><i class="fa fa-eye"></i></span>
            Lihat Detail
        </a>
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
                    <h4 class="card-title">Form Edit Periode Pendaftaran</h4>
                    <div class="ms-auto">
                        <span class="badge badge-primary">ID: {{ $periodePendaftaran->id }}</span>
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

                <form action="{{ route('periode-pendaftaran.update', $periodePendaftaran) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="nama_periode">Nama Periode <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_periode') is-invalid @enderror" 
                               id="nama_periode" 
                               name="nama_periode" 
                               value="{{ old('nama_periode', $periodePendaftaran->nama_periode) }}"
                               placeholder="Contoh: Periode 1 Gelombang 1 Reguler"
                               required>
                        @error('nama_periode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3"
                                  placeholder="Deskripsi tambahan untuk periode ini (opsional)">{{ old('deskripsi', $periodePendaftaran->deskripsi) }}</textarea>
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
                                        <option value="{{ $gelombang->id }}" 
                                                {{ old('gelombang_id', $periodePendaftaran->gelombang_id) == $gelombang->id ? 'selected' : '' }}>
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
                                        <option value="{{ $jalur->id }}" 
                                                {{ old('jalur_pendaftaran_id', $periodePendaftaran->jalur_pendaftaran_id) == $jalur->id ? 'selected' : '' }}>
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
                            <option value="">-- Loading biaya pendaftaran... --</option>
                        </select>
                        @error('biaya_pendaftaran_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                       value="{{ old('tanggal_mulai', $periodePendaftaran->tanggal_mulai->format('Y-m-d')) }}"
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
                                       value="{{ old('tanggal_selesai', $periodePendaftaran->tanggal_selesai->format('Y-m-d')) }}"
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
                                       value="{{ old('kuota', $periodePendaftaran->kuota) }}"
                                       min="{{ $periodePendaftaran->kuota_terisi }}"
                                       max="10000"
                                       required>
                                @error('kuota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Minimal {{ $periodePendaftaran->kuota_terisi }} (kuota terisi), maksimal 10.000
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
                                    <option value="draft" {{ old('status', $periodePendaftaran->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="aktif" {{ old('status', $periodePendaftaran->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $periodePendaftaran->status) == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                                    <option value="selesai" {{ old('status', $periodePendaftaran->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section Syarat Dokumen -->
                    <div class="form-group">
                        <label class="form-label fw-bold">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            Syarat Dokumen Pendaftaran
                        </label>
                        <p class="text-muted mb-3">Pilih dokumen yang diperlukan untuk periode pendaftaran ini</p>
                        
                        @if($dokumenPendaftars->count() > 0)
                            @php
                                $selectedDokumenIds = old('dokumen_pendaftar_ids', $periodePendaftaran->dokumenPendaftars->pluck('id')->toArray());
                                $dokumenPivotData = $periodePendaftaran->dokumenPendaftars->keyBy('id');
                            @endphp
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                            <th>Nama Dokumen</th>
                                            <th>Tipe</th>
                                            <th width="120" class="text-center">Wajib</th>
                                            <th>Catatan Khusus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dokumenPendaftars as $dokumen)
                                        @php
                                            $isSelected = in_array($dokumen->id, $selectedDokumenIds);
                                            $pivotData = $dokumenPivotData->get($dokumen->id);
                                            $isWajib = old("dokumen_wajib.{$dokumen->id}") ?? ($pivotData?->pivot->is_wajib ?? ($dokumen->tipe_dokumen == 'wajib'));
                                            $catatan = old("dokumen_catatan.{$dokumen->id}") ?? ($pivotData?->pivot->catatan ?? '');
                                        @endphp
                                        <tr>
                                            <td>
                                                <input type="checkbox" 
                                                       name="dokumen_pendaftar_ids[]" 
                                                       value="{{ $dokumen->id }}"
                                                       class="form-check-input dokumen-checkbox"
                                                       {{ $isSelected ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <strong>{{ $dokumen->nama_dokumen }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $dokumen->tipe_dokumen == 'wajib' ? 'danger' : 'info' }}">
                                                    {{ ucfirst($dokumen->tipe_dokumen) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" 
                                                       name="dokumen_wajib[{{ $dokumen->id }}]"
                                                       value="1"
                                                       class="form-check-input wajib-checkbox"
                                                       {{ $isWajib ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="dokumen_catatan[{{ $dokumen->id }}]"
                                                       class="form-control form-control-sm"
                                                       placeholder="Catatan khusus (opsional)"
                                                       value="{{ $catatan }}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i>
                                <strong>Tips:</strong> Centang dokumen yang diperlukan, kemudian tentukan apakah dokumen tersebut wajib atau opsional untuk periode ini.
                            </small>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Belum ada dokumen pendaftar yang tersedia. 
                                <a href="{{ route('dokumen-pendaftar.create') }}" class="alert-link">Tambah dokumen pendaftar</a> terlebih dahulu.
                            </div>
                        @endif
                    </div>

                    <div class="card-action">
                        <button type="submit" class="btn btn-success">
                            <span class="btn-label"><i class="fa fa-check"></i></span>
                            Update Data
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
        <div class="card card-info card-round">
            <div class="card-body">
                <h5 class="card-title text-white">
                    <i class="fas fa-info-circle"></i> Info Periode Saat Ini
                </h5>
                <div class="separator-solid"></div>
                <div class="text-white">
                    <p><strong>Periode:</strong> {{ $periodePendaftaran->nama_periode }}</p>
                    <p><strong>Kuota Terisi:</strong> {{ $periodePendaftaran->kuota_terisi }}/{{ $periodePendaftaran->kuota }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge badge-{{ $periodePendaftaran->status_badge }}">
                            {{ ucfirst($periodePendaftaran->status) }}
                        </span>
                    </p>
                    <p><strong>Dibuat:</strong><br>{{ $periodePendaftaran->created_at->format('d F Y, H:i') }}</p>
                    <p><strong>Diubah:</strong><br>{{ $periodePendaftaran->updated_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        @if($periodePendaftaran->kuota_terisi > 0)
        <div class="card card-warning card-round">
            <div class="card-body text-center">
                <div class="card-opening text-white">Peringatan!</div>
                <div class="card-desc">
                    <p class="text-white">Periode ini sudah memiliki {{ $periodePendaftaran->kuota_terisi }} pendaftar. Hati-hati dalam mengubah data!</p>
                </div>
            </div>
        </div>
        @endif

        <div class="card card-secondary card-round">
            <div class="card-body text-center">
                <div class="card-opening text-white">Aksi Lain</div>
                <div class="card-detail">
                    <div class="d-grid gap-2">
                        <a href="{{ route('periode-pendaftaran.show', $periodePendaftaran) }}" class="btn btn-light btn-round btn-sm">
                            <i class="fa fa-eye"></i> Lihat Detail
                        </a>
                        <button type="button" 
                                class="btn btn-danger btn-round btn-sm"
                                onclick="confirmDelete()">
                            <i class="fa fa-trash"></i> Hapus Periode
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form for Delete -->
<form id="deleteForm" action="{{ route('periode-pendaftaran.destroy', $periodePendaftaran) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
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

    // Current selected values
    const currentBiayaId = '{{ old('biaya_pendaftaran_id', $periodePendaftaran->biaya_pendaftaran_id) }}';

    // Form submission dengan loading modal
    $('form:not(#deleteForm)').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Tampilkan modal loading dengan animasi
        $('#loadingMessage').text('Memperbarui periode pendaftaran...');
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
                    
                    // Select current biaya if matches
                    if (biaya.id == currentBiayaId) {
                        option.selected = true;
                    }
                    
                    biayaSelect.appendChild(option);
                });

                // Trigger change event to show biaya info
                if (biayaSelect.value) {
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

    // Load biaya saat halaman load (untuk existing data)
    if (jalurSelect.value) {
        jalurSelect.dispatchEvent(new Event('change'));
    }

    // === Dokumen Management ===
    const selectAllCheckbox = document.getElementById('selectAll');
    const dokumenCheckboxes = document.querySelectorAll('.dokumen-checkbox');
    const wajibCheckboxes = document.querySelectorAll('.wajib-checkbox');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            dokumenCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                toggleRowState(checkbox);
            });
        });
    }

    // Individual checkbox handling
    dokumenCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleRowState(this);
            updateSelectAllState();
        });
        
        // Initialize row state
        toggleRowState(checkbox);
    });

    function toggleRowState(checkbox) {
        const row = checkbox.closest('tr');
        const wajibCheckbox = row.querySelector('.wajib-checkbox');
        const catatanInput = row.querySelector('input[name^="dokumen_catatan"]');
        
        if (checkbox.checked) {
            row.classList.add('table-active');
            wajibCheckbox.disabled = false;
            catatanInput.disabled = false;
        } else {
            row.classList.remove('table-active');
            wajibCheckbox.disabled = true;
            wajibCheckbox.checked = false;
            catatanInput.disabled = true;
            catatanInput.value = '';
        }
    }

    function updateSelectAllState() {
        if (selectAllCheckbox) {
            const checkedCount = document.querySelectorAll('.dokumen-checkbox:checked').length;
            const totalCount = dokumenCheckboxes.length;
            
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            selectAllCheckbox.checked = checkedCount === totalCount;
        }
    }

    // Initialize select all state
    updateSelectAllState();
});

function confirmDelete() {
    swal({
        title: "Hapus Periode Pendaftaran?",
        text: "Data akan dihapus secara permanen!",
        type: "warning",
        buttons: {
            cancel: {
                visible: true,
                text: "Batal",
                className: "btn btn-danger"
            },
            confirm: {
                text: "Ya, Hapus!",
                className: "btn btn-success"
            }
        }
    }).then((willDelete) => {
        if (willDelete) {
            // Tampilkan modal loading untuk delete
            $('#loadingMessage').text('Menghapus periode pendaftaran...');
            $('#loadingModal').modal('show');
            
            // Update delete button
            $('button[onclick="confirmDelete()"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');
            
            // Submit form delete dengan delay
            setTimeout(() => {
                document.getElementById('deleteForm').submit();
            }, 300);
        }
    });
}
</script>
@endpush