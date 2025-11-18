@extends('pmb.layouts.app')

@section('title', 'Pengumuman')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">📢 Pengumuman</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Informasi terkini mengenai status pendaftaran Anda</p>
        </div>

        @if(empty($pengumumanData))
            <!-- Tidak ada data pendaftaran -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum Ada Pendaftaran</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki data pendaftaran. Silakan daftar terlebih dahulu.</p>
                <div class="mt-6">
                    <a href="{{ route('pmb.pendaftaran.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Mulai Pendaftaran
                    </a>
                </div>
            </div>
        @else
            <!-- List Pengumuman -->
            <div class="space-y-6">
                @foreach($pengumumanData as $data)
                    @php
                        $pendaftar = $data['pendaftar'];
                        $pembayaran = $data['pembayaran'];
                        $statusPendaftar = $data['status_pendaftar'];
                        $statusPembayaran = $data['status_pembayaran'];
                        $lolosTahapPendaftaran = $data['lolos_tahap_pendaftaran'];
                    @endphp

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                        <!-- Info Pendaftaran -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $pendaftar->nomor_pendaftaran }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Periode: {{ optional($pendaftar->periodePendaftaran)->nama_periode ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Tanggal Daftar: {{ $pendaftar->created_at->translatedFormat('d M Y H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="mb-1">
                                    <span class="text-xs text-gray-500">Status Dokumen:</span>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                        @if($statusPendaftar === 'submitted')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($statusPendaftar === 'rejected')
                                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else
                                            bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                                        @endif
                                    ">
                                        @if($statusPendaftar === 'submitted')
                                            Terverifikasi
                                        @elseif($statusPendaftar === 'rejected')
                                            Ditolak
                                        @elseif($statusPendaftar === 'draft')
                                            Menunggu Verifikasi
                                        @else
                                            {{ ucfirst($statusPendaftar) }}
                                        @endif
                                    </span>
                                </div>
                                @if($pembayaran)
                                    <div>
                                        <span class="text-xs text-gray-500">Status Pembayaran:</span>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                            @if($statusPembayaran === 'confirmed')
                                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($statusPembayaran === 'pending')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($statusPembayaran === 'rejected')
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else
                                                bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200
                                            @endif
                                        ">
                                            @if($statusPembayaran === 'confirmed')
                                                Lunas
                                            @elseif($statusPembayaran === 'pending')
                                                Menunggu Verifikasi
                                            @elseif($statusPembayaran === 'rejected')
                                                Ditolak
                                            @else
                                                {{ ucfirst($statusPembayaran) }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Pengumuman Berdasarkan Status -->
                        @if($lolosTahapPendaftaran)
                            <!-- LOLOS TAHAP PENDAFTARAN -->
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-lg font-bold text-green-800 dark:text-green-200 mb-2">
                                            🎉 Selamat! Anda Lolos Tahap Pendaftaran
                                        </h4>
                                        <div class="text-sm text-green-700 dark:text-green-300 space-y-2">
                                            <p class="font-medium">
                                                Pendaftaran Anda dengan nomor <strong>{{ $pendaftar->nomor_pendaftaran }}</strong> 
                                                telah berhasil diverifikasi dan pembayaran telah dikonfirmasi.
                                            </p>
                                            <div class="bg-white dark:bg-gray-800 rounded-md p-3 border border-green-200 dark:border-green-700">
                                                <p class="font-semibold text-green-800 dark:text-green-200 mb-2">📋 Status Saat Ini:</p>
                                                <ul class="space-y-1 text-sm">
                                                    <li class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Dokumen pendaftaran terverifikasi
                                                    </li>
                                                    <li class="flex items-center">
                                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Pembayaran biaya pendaftaran dikonfirmasi
                                                    </li>
                                                    <li class="flex items-center">
                                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Menunggu pengumuman tahap seleksi berikutnya
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-md p-3 border border-blue-200 dark:border-blue-700">
                                                <p class="font-semibold text-blue-800 dark:text-blue-200 mb-1">📅 Tahap Selanjutnya:</p>
                                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                                    Silakan pantau pengumuman secara berkala untuk informasi mengenai:
                                                </p>
                                                <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 list-disc list-inside space-y-1">
                                                    <li>Jadwal tes masuk (jika ada)</li>
                                                    <li>Pengumuman kelulusan akhir</li>
                                                    <li>Prosedur daftar ulang</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif($statusPendaftar === 'submitted' && $statusPembayaran === 'pending')
                            <!-- MENUNGGU KONFIRMASI PEMBAYARAN -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            Menunggu Konfirmasi Pembayaran
                                        </h4>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>Dokumen Anda telah diverifikasi. Menunggu konfirmasi pembayaran dari admin.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif($statusPendaftar === 'submitted' && !$pembayaran)
                            <!-- BELUM BAYAR -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                            Silakan Lakukan Pembayaran
                                        </h4>
                                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                            <p>Dokumen Anda telah diverifikasi. Silakan lakukan pembayaran biaya pendaftaran untuk melanjutkan proses.</p>
                                            <div class="mt-3">
                                                <a href="{{ route('pmb.pembayaran.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                    Bayar Sekarang
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif($statusPendaftar === 'rejected')
                            <!-- DOKUMEN DITOLAK -->
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            Dokumen Ditolak
                                        </h4>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                            <p>Dokumen pendaftaran Anda ditolak. Silakan upload ulang dokumen yang sesuai dengan ketentuan.</p>
                                            <div class="mt-3">
                                                <a href="{{ route('pmb.riwayat.edit', $pendaftar->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                    Upload Ulang Dokumen
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <!-- STATUS LAINNYA -->
                            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                            Pendaftaran Dalam Proses
                                        </h4>
                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <p>Pendaftaran Anda sedang dalam tahap pemrosesan. Silakan pantau status secara berkala.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Info Tambahan -->
            <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            💡 Informasi Penting
                        </h4>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pantau halaman pengumuman secara berkala untuk update terbaru</li>
                                <li>Pastikan data kontak Anda selalu aktif untuk menerima notifikasi</li>
                                <li>Simpan nomor pendaftaran Anda untuk keperluan administrasi</li>
                                <li>Hubungi admin jika ada pertanyaan terkait status pendaftaran</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
