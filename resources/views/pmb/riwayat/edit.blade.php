@extends('pmb.layouts.app')

@section('title', 'Upload Ulang Dokumen')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('pmb.riwayat.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L9 3.414V19a1 1 0 0 0 2 0V3.414l7.293 7.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Riwayat Pendaftaran
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Upload Ulang Dokumen</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
        <!-- Header Info -->
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Dokumen Ditolak</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>Dokumen Anda untuk pendaftaran <strong>{{ $pendaftar->nomor_pendaftaran }}</strong> telah ditolak. Silakan upload ulang dokumen yang sesuai dengan ketentuan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Pendaftaran -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-xs text-gray-500 dark:text-gray-400">Nomor Pendaftaran</p>
                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $pendaftar->nomor_pendaftaran }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-xs text-gray-500 dark:text-gray-400">Periode</p>
                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ optional($pendaftar->periodePendaftaran)->nama_periode ?? '-' }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                    Ditolak
                </span>
            </div>
        </div>

        <!-- Form Upload Dokumen -->
        <form action="{{ route('pmb.riwayat.update', $pendaftar->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Upload Ulang Dokumen</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Silakan upload dokumen yang telah diperbaiki sesuai dengan ketentuan yang berlaku.</p>
            </div>

            <!-- Dokumen Lama (jika ada) -->
            @if($pendaftar->documents->isNotEmpty())
                <div class="mb-6">
                    <h4 class="text-md font-medium text-gray-700 dark:text-gray-200 mb-3">Dokumen Sebelumnya (Ditolak)</h4>
                    <div class="space-y-2">
                        @foreach($pendaftar->documents as $doc)
                            <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $doc->catatan ?? 'Dokumen' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Diupload: {{ $doc->created_at->translatedFormat('d M Y H:i') }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200 rounded">Ditolak</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upload Dokumen Baru -->
            <div class="grid grid-cols-1 gap-6">
                <!-- KTP -->
                <div>
                    <label for="ktp" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        KTP <span class="text-red-500">*</span>
                    </label>
                    <div id="ktp-upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 focus-within:border-blue-500 transition-all duration-200">
                        <div class="space-y-1 text-center">
                            <svg id="ktp-upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="ktp" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="ktp-upload-text">Upload KTP</span>
                                    <input id="ktp" name="ktp" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only" required>
                                </label>
                                <p class="pl-1" id="ktp-drag-text">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="ktp-format-text">PDF, PNG, JPG hingga 2MB</p>
                            <div id="ktp-file-info" class="hidden mt-2 text-sm text-green-600">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="ktp-file-name"></span>
                            </div>
                        </div>
                    </div>
                    @error('ktp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ijazah -->
                <div>
                    <label for="ijazah" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Ijazah/SKL <span class="text-red-500">*</span>
                    </label>
                    <div id="ijazah-upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 focus-within:border-blue-500 transition-all duration-200">
                        <div class="space-y-1 text-center">
                            <svg id="ijazah-upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="ijazah" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="ijazah-upload-text">Upload Ijazah/SKL</span>
                                    <input id="ijazah" name="ijazah" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only" required>
                                </label>
                                <p class="pl-1" id="ijazah-drag-text">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="ijazah-format-text">PDF, PNG, JPG hingga 2MB</p>
                            <div id="ijazah-file-info" class="hidden mt-2 text-sm text-green-600">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="ijazah-file-name"></span>
                            </div>
                        </div>
                    </div>
                    @error('ijazah')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kartu Keluarga -->
                <div>
                    <label for="kk" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Kartu Keluarga <span class="text-red-500">*</span>
                    </label>
                    <div id="kk-upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 focus-within:border-blue-500 transition-all duration-200">
                        <div class="space-y-1 text-center">
                            <svg id="kk-upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="kk" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="kk-upload-text">Upload Kartu Keluarga</span>
                                    <input id="kk" name="kk" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only" required>
                                </label>
                                <p class="pl-1" id="kk-drag-text">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="kk-format-text">PDF, PNG, JPG hingga 2MB</p>
                            <div id="kk-file-info" class="hidden mt-2 text-sm text-green-600">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="kk-file-name"></span>
                            </div>
                        </div>
                    </div>
                    @error('kk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pas Foto -->
                <div>
                    <label for="pas_foto" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Pas Foto <span class="text-red-500">*</span>
                    </label>
                    <div id="pas_foto-upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 focus-within:border-blue-500 transition-all duration-200">
                        <div class="space-y-1 text-center">
                            <svg id="pas_foto-upload-icon" class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="pas_foto" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span id="pas_foto-upload-text">Upload Pas Foto</span>
                                    <input id="pas_foto" name="pas_foto" type="file" accept=".jpg,.jpeg,.png" class="sr-only" required>
                                </label>
                                <p class="pl-1" id="pas_foto-drag-text">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500" id="pas_foto-format-text">JPG, PNG hingga 1MB</p>
                            <div id="pas_foto-file-info" class="hidden mt-2 text-sm text-green-600">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span id="pas_foto-file-name"></span>
                            </div>
                        </div>
                    </div>
                    @error('pas_foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea id="catatan" name="catatan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" placeholder="Tuliskan catatan atau keterangan tambahan jika diperlukan..."></textarea>
                @error('catatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('pmb.riwayat.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Upload Ulang Dokumen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // File upload handler dengan visual feedback
    function handleFileSelect(input) {
        const file = input.files[0];
        const fieldName = input.id;
        
        if (file) {
            // Update visual feedback
            updateFileDisplay(fieldName, file);
            
            // Log untuk debugging
            console.log('File selected:', file.name, 'Size:', formatFileSize(file.size));
        } else {
            // Reset display jika tidak ada file
            resetFileDisplay(fieldName);
        }
    }

    // Update tampilan setelah file dipilih
    function updateFileDisplay(fieldName, file) {
        const uploadArea = document.getElementById(fieldName + '-upload-area');
        const uploadIcon = document.getElementById(fieldName + '-upload-icon');
        const uploadText = document.getElementById(fieldName + '-upload-text');
        const dragText = document.getElementById(fieldName + '-drag-text');
        const formatText = document.getElementById(fieldName + '-format-text');
        const fileInfo = document.getElementById(fieldName + '-file-info');
        const fileName = document.getElementById(fieldName + '-file-name');

        // Ubah border menjadi hijau dan solid
        uploadArea.classList.remove('border-gray-300', 'border-dashed');
        uploadArea.classList.add('border-green-500', 'border-solid', 'bg-green-50');

        // Ubah ikon menjadi hijau
        uploadIcon.classList.remove('text-gray-400');
        uploadIcon.classList.add('text-green-500');

        // Update teks
        uploadText.textContent = 'File Terpilih';
        dragText.textContent = 'Klik untuk ganti file';
        
        // Sembunyikan format text
        formatText.classList.add('hidden');
        
        // Tampilkan info file
        fileInfo.classList.remove('hidden');
        fileName.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
    }

    // Reset tampilan ke kondisi awal
    function resetFileDisplay(fieldName) {
        const uploadArea = document.getElementById(fieldName + '-upload-area');
        const uploadIcon = document.getElementById(fieldName + '-upload-icon');
        const uploadText = document.getElementById(fieldName + '-upload-text');
        const dragText = document.getElementById(fieldName + '-drag-text');
        const formatText = document.getElementById(fieldName + '-format-text');
        const fileInfo = document.getElementById(fieldName + '-file-info');

        // Reset border
        uploadArea.classList.remove('border-green-500', 'border-solid', 'bg-green-50');
        uploadArea.classList.add('border-gray-300', 'border-dashed');

        // Reset ikon
        uploadIcon.classList.remove('text-green-500');
        uploadIcon.classList.add('text-gray-400');

        // Reset teks berdasarkan field
        const originalTexts = {
            'ktp': 'Upload KTP',
            'ijazah': 'Upload Ijazah/SKL',
            'kk': 'Upload Kartu Keluarga',
            'pas_foto': 'Upload Pas Foto'
        };

        uploadText.textContent = originalTexts[fieldName] || 'Upload File';
        dragText.textContent = 'atau drag and drop';
        
        // Tampilkan format text
        formatText.classList.remove('hidden');
        
        // Sembunyikan info file
        fileInfo.classList.add('hidden');
    }

    // Format ukuran file
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Add event listeners ke semua file inputs
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            handleFileSelect(this);
        });
    });

    // Drag and drop functionality
    document.querySelectorAll('[id$="-upload-area"]').forEach(uploadArea => {
        const fieldName = uploadArea.id.replace('-upload-area', '');
        const fileInput = document.getElementById(fieldName);

        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(fileInput);
            }
        });
    });
</script>
@endpush