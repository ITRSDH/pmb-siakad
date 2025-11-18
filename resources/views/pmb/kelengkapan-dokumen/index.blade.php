@extends('pmb.layouts.app')

@section('title', 'Kelengkapan Dokumen')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Kelengkapan Dokumen</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Upload dan kelola dokumen pendaftaran Anda</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periode</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $pendaftar->periodePendaftaran->nama_periode }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Pembayaran</label>
                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3"/>
                        </svg>
                        Dikonfirmasi
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Kelengkapan Dokumen</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Status: {{ $dokumenTerupload->count() }} dari {{ $dokumenDiperlukan->count() }} dokumen wajib telah diupload</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('pmb.dokumen.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Upload Dokumen
            </a>
        </div>
    </div>

    <!-- Daftar Dokumen yang Diperlukan -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                Dokumen yang Diperlukan
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Daftar dokumen yang harus diupload untuk periode pendaftaran ini
            </p>
        </div>
        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($dokumenDiperlukan as $dokumen)
                @php
                    $uploaded = $dokumenTerupload->where('dokumen_pendaftar_id', $dokumen->id)->first();
                    $isWajib = $dokumen->pivot->is_wajib;
                @endphp
                <li class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($uploaded)
                                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $dokumen->nama_dokumen }}
                                    @if($isWajib)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Wajib
                                        </span>
                                    @else
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Opsional
                                        </span>
                                    @endif
                                </div>
                                @if($dokumen->pivot->catatan)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $dokumen->pivot->catatan }}
                                    </div>
                                @endif
                                @if($uploaded)
                                    <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                        ✓ Sudah diupload pada {{ $uploaded->created_at->format('d M Y H:i') }}
                                    </div>
                                @else
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                        ⚠ Belum diupload
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($uploaded)
                                <a href="{{ route('pmb.dokumen.show', $uploaded) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Lihat
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('pmb.dokumen.edit', $uploaded) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                    Ganti
                                </a>
                            @else
                                <a href="{{ route('pmb.dokumen.create', ['dokumen' => $dokumen->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    Upload
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    @if($dokumenTerupload->count() > 0)
        <!-- Daftar Dokumen yang Sudah Diupload -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    Dokumen yang Sudah Diupload ({{ $dokumenTerupload->count() }})
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    File-file dokumen yang telah Anda upload
                </p>
            </div>
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($dokumenTerupload as $document)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @php
                                            $ext = pathinfo($document->alamat_dokumen, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($ext), ['jpg','jpeg','png']);
                                            $isPdf = strtolower($ext) === 'pdf';
                                        @endphp
                                        @if($isImage)
                                            <svg class="h-10 w-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @elseif($isPdf)
                                            <svg class="h-10 w-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        @else
                                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $document->dokumenPendaftar ? $document->dokumenPendaftar->nama_dokumen : 'Dokumen Tidak Diketahui' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ basename($document->alamat_dokumen) }}
                                        </div>
                                        @if($document->catatan)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                {{ \Illuminate\Support\Str::limit($document->catatan, 60) }}
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            Diupload {{ $document->created_at->format('d M Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('pmb.dokumen.show', $document) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Lihat
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('pmb.dokumen.edit', $document) }}" class="text-yellow-600 hover:text-yellow-900 text-sm font-medium">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('pmb.dokumen.destroy', $document) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Yakin ingin menghapus dokumen ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
