@extends('pmb.layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
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
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Pembayaran Sebelumnya Ditolak
                            </h3>
                            <div class="mt-1 text-sm text-red-700 dark:text-red-300">
                                <p>Bukti pembayaran sebelumnya telah ditolak oleh admin. Silakan upload bukti pembayaran yang baru dan pastikan file jelas dan sesuai dengan nominal yang tertera.</p>
                                {{-- @if($latestPayment->catatan)
                                    <p class="mt-2"><strong>Catatan Admin:</strong> {{ $latestPayment->catatan }}</p>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mt-4 mb-6 p-4 rounded bg-gray-50 dark:bg-gray-700">
                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $pendaftar->nomor_pendaftaran }}</div>
                <div class="text-sm text-gray-500">Periode: {{ $periode->nama_periode ?? '-' }}</div>
                <div class="text-sm text-gray-500">Total Bayar: <span
                        class="font-semibold text-indigo-700 dark:text-indigo-300">Rp
                        {{ number_format($biaya, 0, ',', '.') }}</span></div>
            </div>


            <form method="POST" action="{{ route('pmb.pembayaran.store', $pendaftar->id) }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required
                        class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="transfer">Transfer Bank</option>
                    </select>
                    @error('metode_pembayaran')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Tanggal
                        Pembayaran</label>
                    <input type="hidden" name="tanggal_pembayaran" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                    <input type="text" readonly
                        class="block w-full rounded-md border border-gray-300 bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600 focus:outline-none"
                        value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Bukti Pembayaran
                        (PDF/JPG/PNG)</label>
                    <input type="file" name="bukti_pembayaran" accept=".pdf,.jpg,.jpeg,.png" required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-white dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    @error('bukti_pembayaran')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Catatan
                        (opsional)</label>
                    <textarea name="catatan" rows="2"
                        class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('pmb.pembayaran.index') }}"
                        class="text-sm text-gray-600 dark:text-gray-300">Kembali</a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Upload
                        Bukti</button>
                </div>
            </form>
        </div>
    </div>
@endsection
