@extends('pmb.layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div x-data="{ loading: false }" class="relative">

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

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upload Bukti Pembayaran</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Unggah bukti transfer pembayaran pendaftaran Anda.</p>

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
                    <a href="{{ route('pmb.pembayaran.index') }}" class="text-sm text-gray-600">Kembali</a>

                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                        Upload Bukti
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
