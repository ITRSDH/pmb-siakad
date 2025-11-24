@extends('pmb.layouts.app')

@section('title', 'Edit Dokumen')

@section('content')
    <div x-data="{ loading: false }" class="relative">

        <!-- Overlay -->
        <div x-show="loading" x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50" style="display: none;">
            <div class="flex flex-col items-center">
                <!-- Spinner -->
                <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-40" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>

                <p class="text-white mt-4 text-sm">Menyimpan perubahan...</p>
            </div>
        </div>
        <!-- END Overlay -->

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"
                                    aria-hidden="true">
                                    <path
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">Edit Dokumen</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Dokumen</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Ubah atau ganti dokumen yang telah diupload</p>
            </div>

            <!-- Current Document Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Dokumen Saat Ini</h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p><strong>File:</strong> {{ basename($dokumen->alamat_dokumen) }}</p>
                            <p><strong>Diupload:</strong> {{ $dokumen->created_at->format('d F Y H:i') }}</p>
                            @if ($dokumen->catatan)
                                <p><strong>Catatan:</strong> {{ $dokumen->catatan }}</p>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('pmb.dokumen.show', $dokumen) }}"
                                class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                Lihat detail dokumen →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('pmb.dokumen.update', $dokumen) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6" @submit="loading = true">
                        @csrf
                        @method('PUT')

                        <!-- File Upload -->
                        <div>
                            <label for="alamat_dokumen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Ganti File Dokumen
                            </label>
                            <div class="mt-1">
                                <input type="file" name="alamat_dokumen" id="alamat_dokumen"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            @error('alamat_dokumen')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Kosongkan jika tidak ingin mengubah file. Format yang didukung: JPG, PNG, PDF, DOC, DOCX.
                                Maksimal 5MB.
                            </p>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Catatan <span class="text-gray-400">(opsional)</span>
                            </label>
                            <div class="mt-1">
                                <textarea id="catatan" name="catatan" rows="4"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"
                                    placeholder="Tambahkan catatan untuk dokumen ini...">{{ old('catatan', $dokumen->catatan) }}</textarea>
                            </div>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Current File -->
                        @php
                            $fileUrl = asset('storage/' . $dokumen->alamat_dokumen);
                            $fileExtension = strtolower(pathinfo($dokumen->alamat_dokumen, PATHINFO_EXTENSION));
                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                            $isPdf = $fileExtension === 'pdf';
                        @endphp

                        @if ($isImage)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Preview File Saat Ini
                                </label>
                                <div class="mt-2 text-center">
                                    <img src="{{ $fileUrl }}" alt="Preview dokumen"
                                        class="mx-auto max-w-xs h-auto rounded-lg shadow-sm">
                                </div>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div
                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Simpan Perubahan
                                </button>

                                <a href="{{ route('pmb.dokumen.show', $dokumen) }}"
                                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Delete Form - Separate from update form -->
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <form action="{{ route('pmb.dokumen.destroy', $dokumen) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus Dokumen
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Tips Upload Dokumen:</h3>
                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Pastikan dokumen jelas dan dapat dibaca</li>
                    <li>• Gunakan format JPG, PNG untuk gambar atau PDF untuk dokumen</li>
                    <li>• Ukuran file maksimal 5MB</li>
                    <li>• Berikan catatan jika diperlukan untuk memperjelas dokumen</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Preview file sebelum upload
        document.getElementById('alamat_dokumen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileType = file.type;
                const fileName = file.name;
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);

                // Create preview info
                let previewHTML = `
            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>File yang dipilih:</strong> ${fileName} (${fileSize} MB)
                </p>
            </div>
        `;

                // Remove existing preview
                const existingPreview = document.querySelector('.file-preview');
                if (existingPreview) {
                    existingPreview.remove();
                }

                // Add new preview
                const previewDiv = document.createElement('div');
                previewDiv.className = 'file-preview';
                previewDiv.innerHTML = previewHTML;
                e.target.parentNode.appendChild(previewDiv);

                // Image preview
                if (fileType.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewDiv.innerHTML += `
                    <div class="mt-3 text-center">
                        <img src="${e.target.result}" alt="Preview" class="mx-auto max-w-xs h-auto rounded-lg shadow-sm">
                    </div>
                `;
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
@endsection
