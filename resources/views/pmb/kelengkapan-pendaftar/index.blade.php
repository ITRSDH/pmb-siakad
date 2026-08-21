@extends('pmb.layouts.app')

@section('title', 'Kelengkapan Pendaftar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Kelengkapan Pendaftar</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Lengkapi data diri Anda untuk verifikasi pendaftaran</p>
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
    @if($pendaftar)
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
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $pendaftar->periodePendaftaran->nama_periode ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Pendaftaran</label>
                        @php
                            $statusPendaftar = $pendaftar->status;
                            $bgColor = match ($statusPendaftar) {
                                'draft' => 'bg-yellow-100',
                                'submitted' => 'bg-green-100',
                                'rejected' => 'bg-red-100',
                                default => 'bg-gray-200',
                            };
                        @endphp
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bgColor }}">
                            <svg class="-ml-0.5 mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3"/>
                            </svg>
                            @if ($pendaftar->status == 'draft')
                                Menunggu Konfirmasi
                            @elseif ($pendaftar->status == 'submitted')
                                Dikonfirmasi
                            @elseif ($pendaftar->status == 'rejected')
                                Ditolak
                            @else
                                {{ ucfirst($pendaftar->status) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Kelengkapan -->
    @if($kelengkapan)
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Status Kelengkapan Data</h3>
                @php
                    $statusBadge = match($kelengkapan->status) {
                        'draft' => 'bg-yellow-100 text-yellow-800',
                        'submitted' => 'bg-blue-100 text-blue-800',
                        'verified' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800'
                    };
                    $statusText = match($kelengkapan->status) {
                        'draft' => 'Draft',
                        'submitted' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => 'Unknown'
                    };
                @endphp
                <div class="flex items-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusBadge }}">
                        @if($kelengkapan->status === 'verified')
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($kelengkapan->status === 'rejected')
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($kelengkapan->status === 'submitted')
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        {{ $statusText }}
                    </span>
                </div>
                
                @if($kelengkapan->status === 'rejected' && $kelengkapan->catatan_admin)
                    <div class="mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Catatan Admin</h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    {{ $kelengkapan->catatan_admin }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Aksi Tersedia</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Kelola data kelengkapan pendaftar Anda</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                @if($kelengkapan->status === 'rejected')
                    <a href="{{ route('pmb.kelengkapan-pendaftar.edit', $kelengkapan->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Perbaiki Data
                    </a>
                @elseif($kelengkapan->status === 'draft')
                    <a href="{{ route('pmb.kelengkapan-pendaftar.edit', $kelengkapan->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Lanjutkan Mengisi
                    </a>
                @else
                    <a href="{{ route('pmb.kelengkapan-pendaftar.show', $kelengkapan->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail
                    </a>
                @endif
            </div>
        </div>
    @elseif($pendaftar)
        <!-- Belum ada kelengkapan -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center">
                    <svg class="h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum Mengisi Data Kelengkapan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda perlu melengkapi data pribadi untuk verifikasi pendaftaran.</p>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('pmb.kelengkapan-pendaftar.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Isi Data Kelengkapan
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Belum terdaftar -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-center">
                    <svg class="h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum Terdaftar</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum terdaftar sebagai pendaftar. Silakan daftar terlebih dahulu.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
