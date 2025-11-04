@extends('pmb.layouts.app')

@section('title', 'Periode Pendaftaran Dibuka')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
	<div class="mb-6">
		<h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">Periode Pendaftaran yang Dibuka</h1>
		<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Berikut adalah periode yang aktif dan sedang berjalan. Silakan pilih salah satu untuk mendaftar.</p>
	</div>

	<!-- Alert jika mahasiswa sudah lolos -->
	@if($hasActiveRegistration)
		<div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
			<div class="flex">
				<div class="flex-shrink-0">
					<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
					</svg>
				</div>
				<div class="ml-3">
					<h3 class="text-sm font-medium text-green-800 dark:text-green-200">
						🎉 Selamat! Anda Sudah Lolos Tahap Pendaftaran
					</h3>
					<div class="mt-2 text-sm text-green-700 dark:text-green-300">
						<p>Anda telah berhasil lolos tahap pendaftaran dan tidak dapat mendaftar ke periode baru. Silakan pantau pengumuman untuk tahap selanjutnya.</p>
						<div class="mt-3">
							<a href="{{ route('pmb.pengumuman.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-800 bg-green-100 hover:bg-green-200 dark:bg-green-800 dark:text-green-200 dark:hover:bg-green-700">
								Lihat Pengumuman
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	@elseif($hasOngoingRegistration && $ongoingRegistration)
		<div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
			<div class="flex">
				<div class="flex-shrink-0">
					<svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
					</svg>
				</div>
				<div class="ml-3">
					<h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
						⏳ Anda Memiliki Pendaftaran yang Sedang Berlangsung
					</h3>
					<div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
						<p>Periode: <strong>{{ $ongoingRegistration->periodePendaftaran->nama_periode }}</strong></p>
						<p>Status: 
							@if($ongoingRegistration->status === 'draft')
								<span class="text-gray-600">Masih Pending - Menunggu Persetujuan</span>
							@elseif($ongoingRegistration->status === 'submitted')
								@php $latestPayment = $ongoingRegistration->payments->last(); @endphp
								@if(!$latestPayment)
									<span class="text-red-600">Belum Bayar</span>
								@elseif($latestPayment->status === 'pending')
									<span class="text-yellow-600">Menunggu Verifikasi Pembayaran</span>
								@elseif($latestPayment->status === 'rejected')
									<span class="text-red-600">Pembayaran Ditolak</span>
								@endif
							@endif
						</p>
						<div class="mt-3 flex gap-2">
							<a href="{{ route('pmb.riwayat.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-800 dark:text-yellow-200 dark:hover:bg-yellow-700">
								Lihat Status Pendaftaran
							</a>
							@if($ongoingRegistration->status === 'submitted' && $ongoingRegistration->payments->last()?->status === 'rejected')
								<a href="{{ route('pmb.pembayaran.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
									Upload Ulang Pembayaran
								</a>
							@elseif($ongoingRegistration->status === 'submitted' && !$ongoingRegistration->payments->count())
								<a href="{{ route('pmb.pembayaran.index') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
									Lakukan Pembayaran
								</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

	<!-- Search / Filters -->
	<form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
		<div>
			<input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari nama periode..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
		</div>
		<div>
			<input type="text" name="jalur" value="{{ $jalur ?? '' }}" placeholder="Filter ID Jalur (opsional)" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
		</div>
		<div class="flex gap-2">
			<input type="text" name="gelombang" value="{{ $gelombang ?? '' }}" placeholder="Filter ID Gelombang (opsional)" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
			<button class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm hover:bg-indigo-700">Filter</button>
		</div>
	</form>

	@if($periodes->count() === 0)
		<div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-gray-600 dark:text-gray-300">
			Tidak ada periode aktif saat ini.
		</div>
	@else
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			@foreach($periodes as $p)
				@php
					$kuotaSisa = $p->kuota_sisa;
					$persen = $p->persentase_kuota;
					$mulai = optional($p->tanggal_mulai)->translatedFormat('d M Y');
					$selesai = optional($p->tanggal_selesai)->translatedFormat('d M Y');
				@endphp
				<div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
					<div class="p-5">
						<div class="flex items-start justify-between">
							<div>
								<h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $p->nama_periode }}</h3>
								<p class="text-xs mt-1 text-gray-600 dark:text-gray-400">
									Gelombang: <span class="font-medium">{{ optional($p->gelombang)->nama_gelombang ?? '-' }}</span> ·
									Jalur: <span class="font-medium">{{ optional($p->jalurPendaftaran)->nama_jalur ?? '-' }}</span>
								</p>
							</div>
							@php
								$badge = ['text' => 'Aktif', 'class' => 'bg-green-100 text-green-700'];
								if ($p->is_pending) { $badge = ['text' => 'Belum Mulai', 'class' => 'bg-yellow-100 text-yellow-700']; }
								elseif ($p->is_expired) { $badge = ['text' => 'Selesai', 'class' => 'bg-gray-100 text-gray-700']; }
								elseif ($p->is_berjalan) { $badge = ['text' => 'Berjalan', 'class' => 'bg-green-100 text-green-700']; }
							@endphp
							<span class="px-2 py-1 rounded-full text-xs {{ $badge['class'] }}">{{ $badge['text'] }}</span>
						</div>
						@if($p->deskripsi)
							<p class="mt-3 text-sm text-gray-700 dark:text-gray-300 line-clamp-3">{{ $p->deskripsi }}</p>
						@endif

						<div class="mt-4 grid grid-cols-3 gap-3 text-sm">
							<div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
								<p class="text-xs text-gray-500 dark:text-gray-400">Mulai</p>
								<p class="font-medium text-gray-900 dark:text-gray-100">{{ $mulai }}</p>
							</div>
							<div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
								<p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
								<p class="font-medium text-gray-900 dark:text-gray-100">{{ $selesai }}</p>
							</div>
							<div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
								<p class="text-xs text-gray-500 dark:text-gray-400">Biaya</p>
								<p class="font-medium text-gray-900 dark:text-gray-100">{{ optional($p->biayaPendaftaran)->jumlah_biaya ? 'Rp '.number_format($p->biayaPendaftaran->jumlah_biaya,0,',','.') : '-' }}</p>
							</div>
						</div>

						<div class="mt-4">
							<div class="flex items-center justify-between text-xs mb-1">
								<span class="text-gray-600 dark:text-gray-400">Kuota Terisi: {{ $p->kuota_terisi }}/{{ $p->kuota }}</span>
								<span class="text-gray-600 dark:text-gray-400">Sisa: {{ $kuotaSisa }}</span>
							</div>
							<div class="w-full h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
								<div class="h-2 bg-indigo-600" style="width: {{ $persen }}%"></div>
							</div>
						</div>

						<div class="mt-5 flex items-center justify-between">
							<a href="{{ route('pmb.pendaftaran.show', $p->id) }}" class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
								Lihat Detail
							</a>
							@php
								$isUserRegistered = isset($userRegistrations[$p->id]) && $userRegistrations[$p->id];
							@endphp
							
							@if($hasActiveRegistration)
								<span class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-green-100 text-green-700 cursor-not-allowed">
									<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
									</svg>
									Sudah Lolos
								</span>
							@elseif(!$canRegisterNew)
								<span class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-yellow-100 text-yellow-700 cursor-not-allowed">
									<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
									</svg>
									Selesaikan Pendaftaran Sebelumnya
								</span>
							@elseif($isUserRegistered)
								<span class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-gray-400 text-white cursor-not-allowed">
									<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
									</svg>
									Sudah Terdaftar
								</span>
							@elseif($p->isAvailable())
								<a href="{{ Route::has('pmb.daftar.mulai') ? route('pmb.daftar.mulai', $p->id) : '#' }}" class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-emerald-600 text-white hover:bg-emerald-700">
									Daftar Sekarang
								</a>
							@else
								<span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Tidak tersedia</span>
							@endif
						</div>
					</div>
				</div>
			@endforeach
		</div>

		<div class="mt-8">{{ $periodes->links() }}</div>
	@endif
</div>
@endsection
