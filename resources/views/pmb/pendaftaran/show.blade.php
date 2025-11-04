@extends('pmb.layouts.app')

@section('title', 'Detail Periode Pendaftaran - ' . $periode->nama_periode)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
	<!-- Breadcrumb -->
	<nav class="flex mb-6" aria-label="Breadcrumb">
		<ol class="inline-flex items-center space-x-1 md:space-x-3">
			<li class="inline-flex items-center">
				<a href="{{ route('pmb.pendaftaran.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
					<svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
						<path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L9 3.414V19a1 1 0 0 0 2 0V3.414l7.293 7.293a1 1 0 0 0 1.414-1.414Z"/>
					</svg>
					Periode Pendaftaran
				</a>
			</li>
			<li>
				<div class="flex items-center">
					<svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
					</svg>
					<span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $periode->nama_periode }}</span>
				</div>
			</li>
		</ol>
	</nav>

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
						🎉 Anda Sudah Lolos Tahap Pendaftaran
					</h3>
					<div class="mt-2 text-sm text-green-700 dark:text-green-300">
						<p>Anda telah berhasil lolos tahap pendaftaran dan tidak dapat mendaftar ke periode baru lagi.</p>
					</div>
				</div>
			</div>
		</div>
	@endif

	<!-- Header Card -->
	<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 mb-8 text-white">
		<div class="flex flex-col md:flex-row md:items-center md:justify-between">
			<div class="flex-1">
				<h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $periode->nama_periode }}</h1>
				<div class="flex flex-wrap items-center gap-4 text-blue-100">
					<div class="flex items-center">
						<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
						</svg>
						{{ optional($periode->gelombang)->nama_gelombang ?? '-' }}
					</div>
					<div class="flex items-center">
						<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
						</svg>
						{{ optional($periode->jalurPendaftaran)->nama_jalur ?? '-' }}
					</div>
				</div>
			</div>
			<div class="mt-4 md:mt-0">
				@php
					$badge = ['text' => 'Aktif', 'class' => 'bg-green-500 text-white'];
					if ($periode->is_pending) { $badge = ['text' => 'Belum Mulai', 'class' => 'bg-yellow-500 text-white']; }
					elseif ($periode->is_expired) { $badge = ['text' => 'Selesai', 'class' => 'bg-gray-500 text-white']; }
					elseif ($periode->is_berjalan) { $badge = ['text' => 'Sedang Berjalan', 'class' => 'bg-green-500 text-white']; }
				@endphp
				<span class="px-4 py-2 rounded-full text-sm font-medium {{ $badge['class'] }}">
					{{ $badge['text'] }}
				</span>
			</div>
		</div>
	</div>

	<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
		<!-- Main Content -->
		<div class="lg:col-span-2">
			<!-- Deskripsi -->
			@if($periode->deskripsi)
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
				<h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Deskripsi</h2>
				<div class="prose prose-gray dark:prose-invert max-w-none">
					<p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $periode->deskripsi }}</p>
				</div>
			</div>
			@endif

			<!-- Timeline -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
				<h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Timeline Pendaftaran</h2>
				<div class="space-y-4">
					<div class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
						<div class="flex-shrink-0">
							<div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center">
								<svg class="w-5 h-5 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
								</svg>
							</div>
						</div>
						<div class="ml-4 flex-1">
							<p class="text-sm font-medium text-gray-900 dark:text-gray-100">Pendaftaran Dibuka</p>
							<p class="text-sm text-gray-600 dark:text-gray-400">{{ optional($periode->tanggal_mulai)->translatedFormat('l, d F Y') ?? '-' }}</p>
						</div>
					</div>
					<div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
						<div class="flex-shrink-0">
							<div class="w-10 h-10 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center">
								<svg class="w-5 h-5 text-red-600 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
								</svg>
							</div>
						</div>
						<div class="ml-4 flex-1">
							<p class="text-sm font-medium text-gray-900 dark:text-gray-100">Pendaftaran Ditutup</p>
							<p class="text-sm text-gray-600 dark:text-gray-400">{{ optional($periode->tanggal_selesai)->translatedFormat('l, d F Y') ?? '-' }}</p>
						</div>
					</div>
				</div>
				@if($periode->tanggal_mulai && $periode->tanggal_selesai)
				<div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
					<p class="text-sm text-gray-600 dark:text-gray-400">
						Durasi pendaftaran: <span class="font-medium text-gray-900 dark:text-gray-100">{{ $periode->getDurasiPendaftaran() }} hari</span>
					</p>
				</div>
				@endif
			</div>

			<!-- Syarat & Ketentuan -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
				<h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Syarat & Ketentuan</h2>
				<div class="space-y-3">
					<div class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
						</svg>
						<p class="text-gray-700 dark:text-gray-300">Mengisi formulir pendaftaran dengan lengkap dan benar</p>
					</div>
					<div class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
						</svg>
						<p class="text-gray-700 dark:text-gray-300">Mengunggah dokumen yang diperlukan (KTP, Ijazah, dll)</p>
					</div>
					<div class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
						</svg>
						<p class="text-gray-700 dark:text-gray-300">Melakukan pembayaran biaya pendaftaran sesuai ketentuan</p>
					</div>
					<div class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
						</svg>
						<p class="text-gray-700 dark:text-gray-300">Mengikuti seluruh proses seleksi yang ditentukan</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Sidebar -->
		<div class="lg:col-span-1">
			<!-- Info Biaya -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
				<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Biaya</h3>
				<div class="text-center">
					<div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
						@if(optional($periode->biayaPendaftaran)->jumlah_biaya)
							Rp {{ number_format($periode->biayaPendaftaran->jumlah_biaya, 0, ',', '.') }}
						@else
							-
						@endif
					</div>
					<p class="text-sm text-gray-600 dark:text-gray-400">
						{{ optional($periode->biayaPendaftaran)->nama_biaya ?? 'Biaya Pendaftaran' }}
					</p>
					@if(optional($periode->biayaPendaftaran)->keterangan)
					<p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
						{{ $periode->biayaPendaftaran->keterangan }}
					</p>
					@endif
				</div>
			</div>

			<!-- Info Kuota -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
				<h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Kuota Pendaftar</h3>
				<div class="space-y-3">
					<div class="flex justify-between items-center">
						<span class="text-sm text-gray-600 dark:text-gray-400">Total Kuota</span>
						<span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($periode->kuota) }}</span>
					</div>
					<div class="flex justify-between items-center">
						<span class="text-sm text-gray-600 dark:text-gray-400">Terisi</span>
						<span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($periode->kuota_terisi) }}</span>
					</div>
					<div class="flex justify-between items-center">
						<span class="text-sm text-gray-600 dark:text-gray-400">Sisa</span>
						<span class="font-semibold text-green-600 dark:text-green-400">{{ number_format($periode->kuota_sisa) }}</span>
					</div>
					<div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
						<div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $periode->persentase_kuota }}%"></div>
					</div>
					<p class="text-xs text-gray-500 dark:text-gray-400 text-center">
						{{ $periode->persentase_kuota }}% terisi
					</p>
				</div>
			</div>

			<!-- Action Button -->
			<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
				@if($isUserRegistered)
					<div class="text-center">
						<div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
							</svg>
						</div>
						<h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Sudah Terdaftar</h4>
						<p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Anda telah mendaftar pada periode ini</p>
						<a href="{{ route('pmb.riwayat.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
							Lihat Riwayat
						</a>
					</div>
				@elseif($hasActiveRegistration)
					<div class="text-center">
						<div class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
							</svg>
						</div>
						<h4 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-2">Sudah Lolos Tahap Pendaftaran</h4>
						<p class="text-sm text-green-600 dark:text-green-400 mb-4">Anda sudah berhasil lolos dan tidak dapat mendaftar ke periode baru</p>
						<a href="{{ route('pmb.pengumuman.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-green-600 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
							<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
							</svg>
							Lihat Pengumuman
						</a>
					</div>
				@elseif(!$canRegisterNew)
					<div class="text-center">
						<div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
							</svg>
						</div>
						<h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Pendaftaran Sedang Berlangsung</h4>
						<p class="text-sm text-yellow-600 dark:text-yellow-400 mb-4">Selesaikan pendaftaran yang sedang berlangsung terlebih dahulu</p>
						<a href="{{ route('pmb.riwayat.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-yellow-600 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
							<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
							</svg>
							Lihat Status Pendaftaran
						</a>
					</div>
				@elseif($periode->isAvailable())
					<div class="text-center">
						<h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Siap Mendaftar?</h4>
						<p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Periode ini tersedia untuk pendaftaran</p>
						<a href="{{ Route::has('pmb.daftar.mulai') ? route('pmb.daftar.mulai', $periode->id) : '#' }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
							<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
							</svg>
							Daftar Sekarang
						</a>
					</div>
				@else
					<div class="text-center">
						<div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
							<svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 008.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
							</svg>
						</div>
						<h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Tidak Tersedia</h4>
						<p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
							@if($periode->is_pending)
								Periode belum dimulai
							@elseif($periode->is_expired)
								Periode telah berakhir
							@elseif($periode->kuota_sisa <= 0)
								Kuota telah penuh
							@else
								Periode tidak aktif
							@endif
						</p>
						<a href="{{ route('pmb.pendaftaran.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
							Lihat Periode Lain
						</a>
					</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection