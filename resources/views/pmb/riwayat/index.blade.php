@extends('pmb.layouts.app')

@section('title', 'Riwayat Pendaftaran')

@section('content')

@if(session('success'))
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Riwayat Pendaftaran Anda</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar pendaftaran yang pernah Anda lakukan.</p>

        @if($pendaftars->isEmpty())
            <div class="mt-6 text-sm text-gray-600 dark:text-gray-300">Belum ada riwayat pendaftaran.</div>
        @else
            <div class="mt-6 space-y-4">
                @foreach($pendaftars as $p)
                    <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-sm text-gray-500">Nomor</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $p->nomor_pendaftaran }}</div>
                                <div class="text-sm text-gray-500">Periode: {{ optional($p->periodePendaftaran)->nama_periode ?? '-' }}</div>
                                <div class="text-sm text-gray-500">
                                    Status:
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                        @if($p->status === 'submitted')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($p->status === 'draft')
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($p->status === 'rejected')
                                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else
                                            bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                                        @endif
                                    ">
                                        @if($p->status === 'draft')
                                            Menunggu Verifikasi
                                        @elseif($p->status === 'submitted')
                                            Diterima
                                        @elseif($p->status === 'rejected')
                                            Ditolak
                                        @else
                                            {{ ucfirst($p->status) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">{{ $p->created_at->translatedFormat('d M Y H:i') }}</div>
                        </div>

                        <!-- Action Buttons untuk Status Rejected -->
                        @if($p->status === 'rejected')
                            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                                <a href="{{ route('pmb.riwayat.edit', $p->id) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Upload Ulang Dokumen
                                </a>
                            </div>
                        @endif

                        @if($p->documents->isNotEmpty())
                            <div class="mt-3">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">Dokumen</h4>
                                <ul class="mt-2 space-y-1 text-sm">
                                    @foreach($p->documents as $d)
                                        <li class="flex items-center justify-between">
                                            <div>
                                                <span class="font-medium">{{ $d->jenis_dokumen ?? $d->catatan ?? 'Dokumen' }}</span>
                                                <div class="text-xs text-gray-500">{{ $d->created_at->diffForHumans() }}</div>
                                            </div>
                                            <div>
                                                @if($d->file_path && Storage::disk('public')->exists($d->file_path))
                                                    <a href="{{ Storage::disk('public')->url($d->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat</a>
                                                @else
                                                    <span class="text-xs text-gray-500">(tidak tersedia)</span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
