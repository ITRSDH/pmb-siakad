@extends('pmb.layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol role="list" class="flex items-center space-x-4">
                <li>
                    <div>
                        <a href="{{ route('pmb.dokumen.index') }}" class="text-gray-400 hover:text-gray-500">
                            Kelengkapan Dokumen
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"/>
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500">Upload Dokumen</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100">Upload Dokumen Baru</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Upload dokumen pendukung untuk melengkapi pendaftaran Anda</p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Terjadi kesalahan!</strong>
            <ul class="mt-2">
                @foreach($errors->all() as $error)
                    <li class="text-sm">• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Info Pendaftar -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Pendaftaran</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Pendaftaran</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $pendaftar->nomor_pendaftaran }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $pendaftar->nama_lengkap }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Upload -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form action="{{ route('pmb.dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Pilihan Jenis Dokumen -->
                <div class="mb-6">
                    <label for="dokumen_pendaftar_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Jenis Dokumen <span class="text-red-500">*</span>
                    </label>
                    <select id="dokumen_pendaftar_id" name="dokumen_pendaftar_id" required class="mt-1 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        @foreach($dokumenDiperlukan as $dokumen)
                            @if(!in_array($dokumen->id, $dokumenTerupload))
                                <option value="{{ $dokumen->id }}" {{ old('dokumen_pendaftar_id') == $dokumen->id ? 'selected' : '' }}>
                                    {{ $dokumen->nama_dokumen }}
                                    @if($dokumen->pivot->is_wajib)
                                        <span style="color: red;">(Wajib)</span>
                                    @else
                                        <span style="color: blue;">(Opsional)</span>
                                    @endif
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('dokumen_pendaftar_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Pilih jenis dokumen yang ingin diupload. Hanya dokumen yang belum diupload yang ditampilkan.
                    </p>
                </div>

                <!-- Informasi Dokumen Terpilih -->
                <div id="dokumen-info" class="mb-6 hidden bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200" id="dokumen-nama">-</h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                <p id="dokumen-keterangan">-</p>
                                <p id="dokumen-status" class="mt-1 font-medium">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="alamat_dokumen" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        File Dokumen <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="alamat_dokumen" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload file</span>
                                    <input id="alamat_dokumen" name="alamat_dokumen" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" required>
                                </label>
                                <p class="pl-1">atau drag dan drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                PDF, JPG, JPEG, PNG hingga 5MB
                            </p>
                        </div>
                    </div>
                    @error('alamat_dokumen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="mb-6">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Catatan/Keterangan
                    </label>
                    <textarea 
                        id="catatan" 
                        name="catatan" 
                        rows="4" 
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                        placeholder="Tambahkan keterangan tentang dokumen ini (opsional)"
                    >{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Opsional: Berikan keterangan atau deskripsi tentang dokumen yang diupload
                    </p>
                </div>

                <!-- Panduan Upload -->
                <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Panduan Upload Dokumen</h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan dokumen jelas dan mudah dibaca</li>
                                    <li>Format file yang didukung: PDF, JPG, JPEG, PNG</li>
                                    <li>Ukuran file maksimal 5MB</li>
                                    <li>Berikan nama file yang deskriptif</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('pmb.dokumen.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview file sebelum upload
document.getElementById('alamat_dokumen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
        
        // Update tampilan
        const uploadText = e.target.closest('.space-y-1').querySelector('label span');
        if (uploadText) {
            uploadText.textContent = `${fileName} (${fileSize} MB)`;
        }
    }
});

// Handle perubahan pilihan dokumen
document.getElementById('dokumen_pendaftar_id').addEventListener('change', function() {
    const dokumenInfo = document.getElementById('dokumen-info');
    const dokumenNama = document.getElementById('dokumen-nama');
    const dokumenKeterangan = document.getElementById('dokumen-keterangan');
    const dokumenStatus = document.getElementById('dokumen-status');
    
    if (this.value) {
        // Data dokumen (dalam implementasi nyata, bisa diambil via AJAX atau disimpan di data attribute)
        const dokumenData = {
            @foreach($dokumenDiperlukan as $dokumen)
                '{{ $dokumen->id }}': {
                    nama: '{{ $dokumen->nama_dokumen }}',
                    keterangan: '{{ $dokumen->pivot->catatan ?? "Tidak ada keterangan khusus" }}',
                    wajib: {{ $dokumen->pivot->is_wajib ? 'true' : 'false' }}
                },
            @endforeach
        };
        
        const selectedDokumen = dokumenData[this.value];
        if (selectedDokumen) {
            dokumenNama.textContent = selectedDokumen.nama;
            dokumenKeterangan.textContent = selectedDokumen.keterangan;
            dokumenStatus.textContent = selectedDokumen.wajib ? '✓ Dokumen Wajib' : 'ℹ Dokumen Opsional';
            dokumenStatus.className = selectedDokumen.wajib ? 
                'mt-1 font-medium text-red-600' : 
                'mt-1 font-medium text-blue-600';
            dokumenInfo.classList.remove('hidden');
        }
    } else {
        dokumenInfo.classList.add('hidden');
    }
});

// Auto-select jika ada parameter dokumen dari URL
const urlParams = new URLSearchParams(window.location.search);
const dokumenParam = urlParams.get('dokumen');
if (dokumenParam) {
    const selectElement = document.getElementById('dokumen_pendaftar_id');
    selectElement.value = dokumenParam;
    selectElement.dispatchEvent(new Event('change'));
}
</script>
@endsection
