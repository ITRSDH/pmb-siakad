@extends('pmb.layouts.app')

@section('title', 'Pembayaran Pendaftaran')

@section('content')
    <div x-data="{ showTataCara: false }" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pembayaran Pendaftaran</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar pendaftaran dan status pembayaran Anda.</p>

            <div class="mt-6 space-y-4">
                @forelse($pendaftars as $p)
                    @php
                        $latest = $p->payments->sortByDesc('created_at')->first();
                        if ($latest?->status === 'confirmed') {
                            $statusBayar = 'Lunas';
                        } elseif ($latest?->status === 'pending') {
                            $statusBayar = 'Menunggu Verifikasi';
                        } elseif ($latest?->status === 'rejected') {
                            $statusBayar = 'Ditolak';
                        } else {
                            $statusBayar = 'Belum Bayar';
                        }
                    @endphp
                    <div
                        class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        @php
                            $periode = optional($p->periodePendaftaran);
                            $biaya = optional($periode->biayaPendaftaran)->jumlah_biaya ?? 0;
                        @endphp
                        <div>
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $p->nomor_pendaftaran }}</div>
                            <div class="text-sm text-gray-500">Periode: {{ $periode->nama_periode ?? '-' }}</div>
                            <div class="text-sm text-gray-500">Status: {{ ucfirst($p->status) }}</div>
                            <div class="text-sm text-gray-500">Biaya: <span
                                    class="font-semibold text-indigo-700 dark:text-indigo-300">Rp
                                    {{ number_format($biaya, 0, ',', '.') }}</span></div>
                        </div>
                        <div class="flex flex-col gap-2 md:items-end">
                            <span
                                class="inline-block px-3 py-1 rounded-full text-xs font-medium
							@if ($statusBayar == 'Belum Bayar') bg-red-100 text-red-700
							@elseif($statusBayar == 'Menunggu Verifikasi') bg-yellow-100 text-yellow-700
							@elseif($statusBayar == 'Ditolak') bg-red-100 text-red-700
							@else bg-green-100 text-green-700 @endif">
                                {{ $statusBayar }}
                            </span>
                            @if ($statusBayar == 'Belum Bayar')
                                <a href="{{ route('pmb.pembayaran.tambah', $p->id) }}"
                                    class="mt-1 inline-block px-4 py-2 rounded-md bg-indigo-600 text-white text-xs hover:bg-indigo-700">Bayar
                                    Sekarang</a>
                                <button type="button"
                                    class="mt-1 inline-block px-4 py-2 rounded-md bg-gray-300 text-gray-800 text-xs hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-100 ml-2"
                                    @click="showTataCara = true">
                                    Tata Cara
                                </button>
                            @elseif($statusBayar == 'Ditolak')
                                <a href="{{ route('pmb.pembayaran.tambah', $p->id) }}"
                                    class="mt-1 inline-block px-4 py-2 rounded-md bg-red-600 text-white text-xs hover:bg-red-700">Upload Ulang Bukti</a>
                                @if($latest && $latest->bukti_pembayaran)
                                    <a href="{{ route('pmb.pembayaran.bukti', $latest->id) }}"
                                        class="mt-1 inline-block px-4 py-2 rounded-md bg-gray-200 text-gray-800 text-xs hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-100">Lihat
                                        Bukti Lama</a>
                                @endif
                            @elseif($latest && $latest->bukti_pembayaran)
                                <a href="{{ route('pmb.pembayaran.bukti', $latest->id) }}"
                                    class="mt-1 inline-block px-4 py-2 rounded-md bg-gray-200 text-gray-800 text-xs hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-100">Lihat
                                    Bukti</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-600 dark:text-gray-300">Belum ada pendaftaran.</div>
                @endforelse
            </div>
        </div>
        <!-- Modal Tata Cara Pembayaran (Alpine.js + Tailwind) -->
        <div @keydown.escape.window="showTataCara = false">
            <div x-show="showTataCara" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" style="display: none;">
                <div @click.away="showTataCara = false" @click.stop class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-lg w-full mx-4">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h5 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tata Cara Pembayaran Pendaftaran</h5>
                        <button @click="showTataCara = false" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-4">
                        <ol class="list-decimal list-inside space-y-2 text-gray-700 dark:text-gray-200">
                            <li>Transfer biaya pendaftaran ke rekening berikut:<br>
                                <span class="font-semibold">Bank BNI 123456789 a.n. Universitas Contoh</span>
                            </li>
                            <li>Pastikan nominal transfer sesuai dengan biaya yang tertera.</li>
                            <li>Simpan bukti transfer dengan jelas (foto/scan).</li>
                            <li>Klik tombol <span class="font-semibold">Bayar Sekarang</span> untuk mengunggah bukti pembayaran.</li>
                            <li>Tunggu verifikasi dari admin. Status pembayaran akan berubah menjadi <span class="font-semibold">Menunggu Verifikasi</span> atau <span class="font-semibold">Lunas</span> setelah diverifikasi.</li>
                        </ol>
                        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                            Jika ada kendala, silakan hubungi admin PMB melalui WhatsApp: <span class="font-semibold">0812-3456-7890</span>
                        </div>
                    </div>
                    <div class="flex justify-end px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                        <button @click="showTataCara = false" class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 text-sm hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan modal tersembunyi saat load halaman
    const modal = document.querySelector('[x-show="showTataCara"]');
    if (modal) {
        modal.style.display = 'none';
    }
    
    // Backup jika Alpine.js tidak aktif
    if (!window.Alpine) {
        let isModalOpen = false;
        
        function showModal() {
            const modal = document.querySelector('[x-show="showTataCara"]');
            if (modal) {
                modal.style.display = 'flex';
                isModalOpen = true;
            }
        }
        
        function hideModal() {
            const modal = document.querySelector('[x-show="showTataCara"]');
            if (modal) {
                modal.style.display = 'none';
                isModalOpen = false;
            }
        }
        
        // Tombol buka modal
        document.querySelectorAll('button[\\@click="showTataCara = true"]').forEach(btn => {
            btn.addEventListener('click', showModal);
        });
        
        // Tombol tutup modal
        document.querySelectorAll('button[\\@click="showTataCara = false"]').forEach(btn => {
            btn.addEventListener('click', hideModal);
        });
        
        // Tutup dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isModalOpen) {
                hideModal();
            }
        });
        
        // Tutup dengan klik di luar modal
        document.addEventListener('click', function(e) {
            if (isModalOpen && e.target.classList.contains('bg-black')) {
                hideModal();
            }
        });
    }
});
</script>
@endpush
