@extends('pmb.layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                        <span class="ml-4 text-sm font-medium text-gray-500">Detail Dokumen</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Dokumen</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Lihat dan kelola dokumen yang telah diupload</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Document Info -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Dokumen</h3>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama File</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ basename($dokumen->alamat_dokumen) }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Dokumen</dt>
                            @if($dokumen->status_dokumen === 'disetujui')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓ Disetujui
                                </span>
                            @elseif($dokumen->status_dokumen === 'ditolak')
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    ✗ Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    ⏱ Menunggu Persetujuan
                                </span>
                            @endif
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe File</dt>
                            <dd class="mt-1">
                                @if($isImage)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Gambar
                                    </span>
                                @elseif($isPdf)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        PDF
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Dokumen
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Upload</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $dokumen->created_at->format('d F Y H:i') }}</dd>
                        </div>
                        
                        @if($dokumen->updated_at != $dokumen->created_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir Diubah</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $dokumen->updated_at->format('d F Y H:i') }}</dd>
                            </div>
                        @endif
                        
                        @if($dokumen->catatan)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $dokumen->catatan }}</dd>
                            </div>
                        @endif
                    </dl>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <a href="{{ $fileUrl }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download File
                        </a>
                        
                        @if($dokumen->status_dokumen !== 'disetujui')
                            <a href="{{ route('pmb.dokumen.edit', $dokumen) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Dokumen
                            </a>
                            
                            <form action="{{ route('pmb.dokumen.destroy', $dokumen) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus dokumen ini?')" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus Dokumen
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('pmb.dokumen.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Preview Dokumen</h3>
                    
                    @if($isImage)
                        <div class="text-center">
                            <img src="{{ $fileUrl }}" alt="Preview dokumen" class="mx-auto max-w-full h-auto rounded-lg shadow-lg">
                        </div>
                    @elseif($isPdf)
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8">
                            <div class="text-center">
                                <svg class="mx-auto h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <h4 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">File PDF</h4>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Klik tombol "Download File" untuk melihat dokumen PDF
                                </p>
                                <div class="mt-4">
                                    <a href="{{ $fileUrl }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Buka PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Embed PDF untuk browser yang mendukung -->
                        <div class="mt-6">
                            <iframe src="{{ $fileUrl }}" width="100%" height="600" class="border rounded-lg"></iframe>
                        </div>
                    @else
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8">
                            <div class="text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h4 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-100">Dokumen</h4>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Preview tidak tersedia untuk tipe file ini. Klik "Download File" untuk mengunduh.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection