@extends('pmb.layouts.app')

@section('title', 'Dashboard PMB - Calon Mahasiswa')

@section('content')
    @php
        $user = auth('google')->user();
        // Optional data expected from controller, with safe fallbacks
        $pendaftar = $pendaftar ?? null; // e.g., instance of App\Models\Pendaftar for this google user
        $periodeAktif = $periodeAktif ?? null; // e.g., current PeriodePendaftaran
        $biayaPendaftaran = $biayaPendaftaran ?? null; // amount object {jumlah}
        $deadline = optional($periodeAktif)->tanggal_selesai ?? null;
        $nomorPendaftaran = optional($pendaftar)->nomor_pendaftaran ?? '-';
        $statusPendaftaran = optional($pendaftar)->status ?? 'Belum Memulai';
        $biodataLengkap = (bool) (optional($pendaftar)->biodata_lengkap ?? false);
        $dokumenLengkap = (bool) (optional($pendaftar)->dokumen_lengkap ?? false);
        $statusBayar = $statusBayar ?? 'Belum Bayar'; // Belum Bayar | Menunggu Verifikasi | Lunas
        $jumlahBiaya = optional($biayaPendaftaran)->jumlah_biaya ?? null; // Perbaiki nama field
        $periodeNama =
            optional($periodeAktif)->nama_periode ??
            (optional(optional($pendaftar)->periodePendaftaran)->nama_periode ?? '-');
        $bayarColor =
            [
                'Belum Bayar' => 'bg-red-100 text-red-700',
                'Menunggu Verifikasi' => 'bg-yellow-100 text-yellow-700',
                'Lunas' => 'bg-green-100 text-green-700',
                'Ditolak' => 'bg-red-100 text-red-700',
            ][$statusBayar] ?? 'bg-gray-100 text-gray-700';
    @endphp

    <div class="min-h-screen">

        <!-- Header / Hero Summary -->
        <header class="bg-gradient-to-r from-indigo-600 to-blue-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
                <div class="text-white">
                    <h1 class="text-2xl sm:text-3xl font-bold">Selamat datang, {{ $user->name }}!</h1>
                    <p class="mt-1 text-indigo-100">
                        @if (isset($hasActiveRegistration) && $hasActiveRegistration)
                            🎉 Selamat! Anda sudah lolos tahap pendaftaran PMB.
                        @elseif(isset($hasOngoingRegistration) && $hasOngoingRegistration)
                            ⏳ Pantau dan lengkapi proses pendaftaran PMB yang sedang berlangsung.
                        @else
                            Pantau dan lengkapi proses Penerimaan Mahasiswa Baru (PMB) Anda di sini.
                        @endif
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white/95 dark:bg-gray-800 rounded-xl p-4 shadow flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Nomor Pendaftaran</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $nomorPendaftaran }}
                            </p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">PMB</span>
                    </div>
                    <div class="bg-white/95 dark:bg-gray-800 rounded-xl p-4 shadow">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Periode Aktif</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $periodeNama }}</p>
                        @if ($deadline)
                            <p class="text-xs mt-1 text-gray-600 dark:text-gray-400">Tutup:
                                {{ \Illuminate\Support\Carbon::parse($deadline)->translatedFormat('d M Y H:i') }}</p>
                        @endif
                    </div>
                    <div class="bg-white/95 dark:bg-gray-800 rounded-xl p-4 shadow">
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Dokumen Pendaftaran</p>
                        @php
                            $statusColor =
                                [
                                    'draft' => 'bg-yellow-100 text-yellow-700',
                                    'submitted' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ][$statusPendaftaran] ?? 'bg-gray-100 text-gray-700';
                            
                            $statusLabel = [
                                'draft' => 'Menunggu Persetujuan',
                                'submitted' => 'Submitted',
                                'rejected' => 'Rejected',
                            ][$statusPendaftaran] ?? $statusPendaftaran;
                        @endphp
                        <span
                            class="inline-flex mt-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColor }}">{{ $statusLabel }}</span>
                    </div>
                </div>

                @if ($deadline)
                    <div class="mt-4 flex items-center gap-2 text-white/90">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m3.75 0a8.25 8.25 0 11-16.5 0 8.25 8.25 0 0116.5 0z" />
                        </svg>
                        <span>Penutupan pendaftaran dalam <span id="countdown"
                                data-deadline="{{ \Illuminate\Support\Carbon::parse($deadline)->toIso8601String() }}">...</span></span>
                    </div>
                @endif
            </div>
        </header>

        <main class="py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Alert Status Pendaftaran -->
                @if (isset($hasActiveRegistration) && $hasActiveRegistration)
                    <div
                        class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    🎉 Selamat! Anda Sudah Lolos Tahap Pendaftaran
                                </h3>
                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                    <p>Data di bawah menampilkan informasi pendaftaran Anda yang sudah berhasil lolos.
                                        Silakan pantau pengumuman untuk tahap selanjutnya.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(isset($hasOngoingRegistration) && $hasOngoingRegistration)
                    <div
                        class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                    ⏳ Pendaftaran Sedang Berlangsung
                                </h3>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <p>Data di bawah menampilkan informasi pendaftaran Anda yang sedang berlangsung.
                                        Lengkapi semua tahapan untuk menyelesaikan proses pendaftaran.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif(!$pendaftar)
                    <div
                        class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    💡 Belum Ada Pendaftaran
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>Anda belum memiliki pendaftaran aktif. Data di bawah menampilkan informasi periode
                                        yang sedang dibuka saat ini.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('pmb.pendaftaran.index') }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-800 bg-blue-100 hover:bg-blue-200 dark:bg-blue-800 dark:text-blue-200 dark:hover:bg-blue-700">
                                            Lihat Periode Pendaftaran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Progress Steps -->
                {{-- <section class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Tahapan Pendaftaran</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Step 1 -->
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 grid place-items-center font-bold">1</div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Lengkapi Biodata</span>
                                </div>
                                @if ($biodataLengkap)
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">Selesai</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700">Belum</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Isi data diri lengkap dan valid sesuai dokumen resmi.</p>
                            <div class="mt-3">
                                @if (isset($pendaftar) && $pendaftar)
                                    <a href="{{ Route::has('pmb.biodata.edit') ? route('pmb.biodata.edit', $pendaftar) : url('#') }}" class="inline-flex items-center gap-2 text-xs px-3 py-2 rounded-md 
                                    {{ $biodataLengkap ? 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                @else
                                    <a href="{{ route('pmb.pendaftaran.index') }}" class="inline-flex items-center gap-2 text-xs px-3 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                @endif
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.862 4.487z"/></svg>
                                    {{ $biodataLengkap ? 'Lihat Biodata' : 'Lengkapi Sekarang' }}
                                </a>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 grid place-items-center font-bold">2</div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Unggah Dokumen</span>
                                </div>
                                @if ($dokumenLengkap)
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">Selesai</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700">Belum</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Unggah dokumen persyaratan (Ijazah/SKL, Nilai, KTP, KK, Pas Foto, dsb.).</p>
                            <div class="mt-3">
                                <a href="{{ Route::has('pmb.dokumen.index') ? route('pmb.dokumen.index') : url('#') }}" class="inline-flex items-center gap-2 text-xs px-3 py-2 rounded-md 
                                    {{ $dokumenLengkap ? 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 7.5 7.5 12M12 7.5V18"/></svg>
                                    {{ $dokumenLengkap ? 'Lihat Dokumen' : 'Unggah Sekarang' }}
                                </a>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 grid place-items-center font-bold">3</div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Pembayaran</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $bayarColor }}">{{ $statusBayar }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                @if ($jumlahBiaya)
                                    Biaya pendaftaran: <span class="font-semibold">Rp {{ number_format($jumlahBiaya,0,',','.') }}</span>.
                                @else
                                    Silakan cek nominal biaya pada menu pembayaran.
                                @endif
                            </p>
                            <div class="mt-3">
                                <a href="{{ Route::has('pmb.pembayaran.index') ? route('pmb.pembayaran.index') : url('#') }}" class="inline-flex items-center gap-2 text-xs px-3 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3v6m3-3H9"/></svg>
                                    Buka Pembayaran
                                </a>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 grid place-items-center font-bold">4</div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Seleksi & Pengumuman</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-700">Info</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Ikuti jadwal seleksi sesuai jalur. Cek pengumuman kelulusan secara berkala.</p>
                            <div class="mt-3">
                                <a href="{{ Route::has('pmb.pengumuman.index') ? route('pmb.pengumuman.index') : url('#') }}" class="inline-flex items-center gap-2 text-xs px-3 py-2 rounded-md bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Lihat Pengumuman
                                </a>
                            </div>
                        </div>
                    </div>
                </section> --}}

                <!-- Payment + Notices -->
                @if ($pendaftar)
                    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Payment Card -->
                        <div
                            class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Status
                                        Pembayaran</h3>
                                    <span
                                        class="text-xs px-2 py-1 rounded-full {{ $bayarColor }}">{{ $statusBayar }}</span>
                                </div>
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Nomor Pendaftaran</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $nomorPendaftaran }}</p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Periode</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $periodeNama }}
                                        </p>
                                    </div>
                                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Biaya Pendaftaran</p>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $jumlahBiaya ? 'Rp ' . number_format($jumlahBiaya, 0, ',', '.') : '-' }}</p>
                                    </div>
                                </div>
                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                                    @if ($statusBayar === 'Ditolak')
                                        Pembayaran Anda ditolak. Silakan lakukan pembayaran ulang sesuai petunjuk pada
                                        halaman
                                        pembayaran.
                                    @elseif($statusBayar === 'Belum Bayar')
                                        Silakan lakukan pembayaran sesuai petunjuk pada halaman pembayaran. Setelah
                                        melakukan
                                        pembayaran, status akan diperbarui otomatis.
                                    @elseif($statusBayar === 'Menunggu Verifikasi')
                                        Pembayaran Anda telah diterima. Mohon tunggu proses verifikasi dari panitia PMB.
                                    @elseif($statusBayar === 'Lunas')
                                        Pembayaran telah terkonfirmasi. Anda dapat menunggu jadwal seleksi/pengumuman
                                        selanjutnya.
                                    @else
                                        Informasi pembayaran akan tersedia setelah melengkapi tahapan awal.
                                    @endif
                                </div>
                                <div class="mt-5">
                                    <a href="{{ Route::has('pmb.pembayaran.index') ? route('pmb.pembayaran.index') : url('#') }}"
                                        class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                        Kelola Pembayaran
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Notices / Info -->
                        <div
                            class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="p-5 sm:p-6">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi
                                    Penting</h3>
                                <ul class="mt-4 space-y-3 text-sm text-gray-700 dark:text-gray-300">
                                    @if (isset($hasActiveRegistration) && $hasActiveRegistration)
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-green-500"></span> Selamat! Anda
                                            telah berhasil lolos tahap pendaftaran.</li>
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-green-500"></span> Pantau menu
                                            Pengumuman untuk informasi tahap selanjutnya.</li>
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-green-500"></span> Simpan nomor
                                            pendaftaran untuk keperluan administrasi.</li>
                                    @elseif(isset($hasOngoingRegistration) && $hasOngoingRegistration)
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Selesaikan semua
                                            tahapan pendaftaran sebelum batas waktu.</li>
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pastikan
                                            pembayaran sudah terkonfirmasi.</li>
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Cek status
                                            pembayaran secara berkala.</li>
                                    @else
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Pastikan
                                            biodata dan dokumen Anda lengkap sebelum batas waktu pendaftaran.</li>
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Simpan nomor
                                            pendaftaran Anda untuk keperluan verifikasi dan pembayaran.</li>
                                        <li class="flex items-start gap-2"><span
                                                class="mt-1 w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Cek menu
                                            Pengumuman untuk jadwal seleksi dan hasil kelulusan.</li>
                                    @endif
                                </ul>
                                <div class="mt-5 grid grid-cols-1 gap-2">
                                    <a href="{{ url('/') }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">Kembali
                                        ke Beranda</a>
                                    @if (isset($pendaftar) && $pendaftar)
                                        <a href="{{ Route::has('pmb.biodata.edit') ? route('pmb.biodata.edit', $pendaftar) : url('#') }}"
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700">Perbarui
                                            Biodata</a>
                                    @else
                                        <a href="{{ route('pmb.pendaftaran.index') }}"
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700">Mulai
                                            Pendaftaran</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                @else
                    <!-- Simple Info Section for Non-Registered Users -->
                    <section class="max-w-2xl mx-auto">
                        <div
                            class="rounded-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="p-6 text-center">
                                <div
                                    class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Selamat Datang di
                                    PMB!</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    Anda belum memiliki pendaftaran aktif. Mari mulai proses pendaftaran untuk periode yang
                                    sedang dibuka.
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <a href="{{ route('pmb.pendaftaran.index') }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 4.5v15m7.5-7.5h-15"></path>
                                        </svg>
                                        Mulai Pendaftaran
                                    </a>
                                    <a href="{{ Route::has('pmb.pengumuman.index') ? route('pmb.pengumuman.index') : url('#') }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46">
                                            </path>
                                        </svg>
                                        Lihat Pengumuman
                                    </a>
                                </div>
                                @if ($periodeAktif)
                                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Periode Aktif Saat Ini:
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $periodeNama }}</p>
                                        @if ($deadline)
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                Tutup:
                                                {{ \Illuminate\Support\Carbon::parse($deadline)->translatedFormat('d M Y H:i') }}
                                            </p>
                                        @endif
                                        @if ($jumlahBiaya)
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                Biaya: Rp {{ number_format($jumlahBiaya, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        // Simple countdown for deadline if available
        (function() {
            const el = document.getElementById('countdown');
            if (!el) return;
            const deadline = el.getAttribute('data-deadline');
            if (!deadline) return;
            const end = new Date(deadline).getTime();

            function tick() {
                const now = Date.now();
                let diff = Math.max(0, end - now);
                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                diff -= d * (1000 * 60 * 60 * 24);
                const h = Math.floor(diff / (1000 * 60 * 60));
                diff -= h * (1000 * 60 * 60);
                const m = Math.floor(diff / (1000 * 60));
                diff -= m * (1000 * 60);
                const s = Math.floor(diff / 1000);
                el.textContent = `${d}h ${h}j ${m}m ${s}d`;
                if (end - now > 0) requestAnimationFrame(() => setTimeout(tick, 1000));
            }
            tick();
        })();
    </script>
@endpush
