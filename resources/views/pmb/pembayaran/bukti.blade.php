
@extends('pmb.layouts.app')

@section('title', 'Lihat Bukti Pembayaran')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
	<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
		<h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Bukti Pembayaran</h2>
		<div class="mb-4">
			<div class="text-sm text-gray-700 dark:text-gray-200">Nomor Pendaftaran: <span class="font-semibold">{{ $pembayaran->pendaftar->nomor_pendaftaran ?? '-' }}</span></div>
			<div class="text-sm text-gray-700 dark:text-gray-200">Tanggal: {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d-m-Y') }}</div>
			<div class="text-sm text-gray-700 dark:text-gray-200">Metode: {{ ucfirst($pembayaran->metode_pembayaran) }}</div>
			<div class="text-sm text-gray-700 dark:text-gray-200">Status: <span class="font-semibold">{{ ucfirst($pembayaran->status) }}</span></div>
		</div>
		<div class="mb-6">
			@if($isImage)
				<img src="{{ $url }}" alt="Bukti Pembayaran" class="rounded shadow max-w-full h-auto mx-auto" />
			@elseif($isPdf)
				<iframe src="{{ $url }}" width="100%" height="500px" class="rounded shadow"></iframe>
				<div class="mt-2 text-center">
					<a href="{{ $url }}" target="_blank" class="text-indigo-600 hover:underline">Buka di tab baru</a>
				</div>
			@else
				<a href="{{ $url }}" target="_blank" class="text-indigo-600 hover:underline">Download Bukti Pembayaran</a>
			@endif
		</div>
		<div class="flex justify-between">
			<a href="{{ route('pmb.pembayaran.index') }}" class="text-sm text-gray-600 dark:text-gray-300">Kembali</a>
		</div>
	</div>
</div>
@endsection
