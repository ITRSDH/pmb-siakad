@extends('pmb.layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div x-data="{ loading: false, showPaymentGuide: false }" class="relative">

    <!-- Overlay -->
    <div 
        x-show="loading" 
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="flex flex-col items-center">
            <!-- Spinner -->
            <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-40" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-90" fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>
            </svg>

            <p class="text-white mt-4 text-sm">Mengunggah bukti pembayaran...</p>
        </div>
    </div>
    <!-- END Overlay -->


    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upload Bukti Pembayaran</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Unggah bukti transfer pembayaran pendaftaran Anda.</p>
                </div>
                <button type="button"
                    @click="showPaymentGuide = true"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tata Cara Pembayaran
                </button>
            </div>

            @php
                $periode = optional($pendaftar->periodePendaftaran);
                $biaya = optional($periode->biayaPendaftaran)->jumlah_biaya ?? 0;
                $latestPayment = $pendaftar->payments->sortByDesc('created_at')->first();
            @endphp

            @if($latestPayment && $latestPayment->status === 'rejected')
                <div class="mt-4 mb-4 p-4 rounded bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 
                                1.414L8.586 10l-1.293 1.293a1 1 0 101.414 
                                1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 
                                10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>

                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Pembayaran Sebelumnya Ditolak
                            </h3>
                            <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                Bukti pembayaran sebelumnya telah ditolak oleh admin.
                                Silakan upload bukti yang baru dan pastikan file jelas.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4 mb-6 p-4 rounded bg-gray-50 dark:bg-gray-700">
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $pendaftar->nomor_pendaftaran }}</div>
                <div class="text-sm text-gray-500">Periode: {{ $periode->nama_periode ?? '-' }}</div>
                <div class="text-sm text-gray-500">Total Bayar:
                    <span class="font-semibold text-indigo-700 dark:text-indigo-300">
                        Rp {{ number_format($biaya, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- FORM -->
            <form 
                method="POST" 
                action="{{ route('pmb.pembayaran.store', $pendaftar->id) }}" 
                enctype="multipart/form-data"
                class="space-y-6"
                @submit="loading = true"
            >
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required
                        class="w-full rounded-md border bg-white dark:bg-gray-800">
                        <option value="transfer">Transfer Bank</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Tanggal Pembayaran</label>
                    <input type="hidden" name="tanggal_pembayaran" 
                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    <input type="text" readonly
                        class="w-full rounded-md bg-gray-100 dark:bg-gray-700"
                        value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Bukti Pembayaran
                        (PDF/JPG/PNG) Max 5MB</label>
                    <input type="file" required name="bukti_pembayaran"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full text-sm border rounded-md bg-white dark:bg-gray-800" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2" class="w-full rounded-md border bg-white dark:bg-gray-800"></textarea>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('pmb.pembayaran.index') }}" class="text-sm inline-flex items-center gap-2 px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Kembali
                    </a>

                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                        Upload Bukti
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Modal Tata Cara Pembayaran -->
    <div x-show="showPaymentGuide"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
        @click.self="showPaymentGuide = false"
    >
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tata Cara Pembayaran</h3>
                    <button type="button"
                        @click="showPaymentGuide = false"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-sm text-gray-700 dark:text-gray-300">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <h4 class="font-semibold text-blue-900 dark:text-blue-200 mb-2">Informasi Pembayaran</h4>
                        <p class="text-gray-700 dark:text-gray-300">
                            Total yang harus dibayar: <span class="font-bold text-indigo-700 dark:text-indigo-300">Rp {{ number_format($biaya, 0, ',', '.') }}</span>
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Langkah-langkah Pembayaran:</h4>
                        <ol class="list-decimal list-inside space-y-2">
                            <li>Lakukan transfer ke rekening bank berikut: <b>norek a/n nama_lengkap (Bank BNI)</b></li>
                            <li>Pastikan nominal yang ditransfer sesuai dengan total biaya pendaftaran</li>
                            <li>Simpan bukti transfer (screenshot/foto)</li>
                            <li>Upload bukti transfer pada form di atas</li>
                            <li>Tunggu konfirmasi dari admin</li>
                        </ol>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <h4 class="font-semibold text-yellow-900 dark:text-yellow-200 mb-2">Catatan Penting:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Pastikan bukti transfer jelas dan terbaca</li>
                            <li>Format file yang diterima: PDF, JPG, PNG (maksimal 5MB)</li>
                            <li>Proses verifikasi membutuhkan waktu 1-2 hari kerja</li>
                            <li>Jika pembayaran ditolak, silakan upload ulang bukti yang baru</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Butuh Bantuan?</h4>
                        <p class="text-gray-700 dark:text-gray-300">
                            Jika mengalami kendala dalam pembayaran, silakan hubungi admin melalui kontak yang tersedia di halaman utama.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button"
                        @click="showPaymentGuide = false"
                        class="px-4 py-2 text-sm rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Modal -->

</div>
@endsection
