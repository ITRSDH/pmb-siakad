@php
	$googleUser = auth('google')->user();
	$dashboardHref = Route::has('google.dashboard') ? route('google.dashboard') : (Route::has('pmb.dashboard') ? route('pmb.dashboard') : url('/'));
	
	// Cek status pembayaran mahasiswa
	$sudahBayar = false;
	if ($googleUser) {
		$pendaftar = \App\Models\Pendaftar::where('google_user_id', $googleUser->id)->first();
		if ($pendaftar) {
			$sudahBayar = \App\Models\PendaftarPembayaran::where('pendaftar_id', $pendaftar->id)
				->where('status', 'confirmed')
				->exists();
		}
	}
	
	$navLinks = [
		[
			'label' => 'Dashboard',
			'href' => $dashboardHref,
			'active' => request()->routeIs('google.dashboard') || request()->routeIs('pmb.dashboard'),
		],
		[
			'label' => 'Periode',
			'href' => (Route::has('pmb.pendaftaran.index') ? route('pmb.pendaftaran.index') : '#'),
			'active' => request()->routeIs('pmb.pendaftaran.*'),
		],
		[
			'label' => 'Pembayaran',
			'href' => (Route::has('pmb.pembayaran.index') ? route('pmb.pembayaran.index') : '#'),
			'active' => request()->routeIs('pmb.pembayaran.*'),
		],
	];
	
	// Tambahkan menu Dokumen hanya jika sudah membayar
	if ($sudahBayar) {
		$navLinks[] = [
			'label' => 'Dokumen',
			'href' => (Route::has('pmb.dokumen.index') ? route('pmb.dokumen.index') : '#'),
			'active' => request()->routeIs('pmb.dokumen.*'),
		];
	}
	
	$navLinks = array_merge($navLinks, [
		[
			'label' => 'Pengumuman',
			'href' => (Route::has('pmb.pengumuman.index') ? route('pmb.pengumuman.index') : '#'),
			'active' => request()->routeIs('pmb.pengumuman.*'),
		],
		[
			'label' => 'Riwayat',
			// header can't assume a pendaftar instance; point to dashboard where biodata management is accessible
			'href' => (Route::has('pmb.riwayat.index') ? route('pmb.riwayat.index') : '#'),
			'active' => request()->routeIs('pmb.riwayat.*'),
		],
	]);
@endphp

<nav class="bg-white/80 dark:bg-gray-800/90 backdrop-blur shadow-sm">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex items-center justify-between h-16">
			<div class="flex items-center gap-6">
				<div class="flex items-center gap-3">
					<svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m8-6H4" />
					</svg>
					<span class="text-lg font-semibold text-gray-900 dark:text-white">PMB – Dashboard</span>
				</div>
				<!-- Desktop Nav -->
				<div class="hidden sm:flex items-center gap-4">
					@foreach($navLinks as $link)
						<a href="{{ $link['href'] }}" class="text-sm {{ $link['active'] ? 'text-indigo-600 dark:text-indigo-400 font-semibold' : 'text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}">
							{{ $link['label'] }}
						</a>
					@endforeach
				</div>
			</div>

			<div class="flex items-center gap-4">
				<!-- Mobile toggle -->
				<button class="sm:hidden inline-flex items-center justify-center w-9 h-9 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700" id="pmb-nav-toggle" aria-label="Toggle Navigation">
					<svg class="w-5 h-5 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
				</button>

				@if($googleUser)
					<div class="hidden sm:flex flex-col items-end">
						<span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $googleUser->name }}</span>
						<span class="text-xs text-gray-500 dark:text-gray-400">{{ $googleUser->email }}</span>
					</div>
					@if($googleUser->avatar)
						<img src="{{ $googleUser->avatar }}" alt="Avatar" class="hidden sm:block w-9 h-9 rounded-full ring-2 ring-indigo-500/20" />
					@else
						<div class="hidden sm:grid w-9 h-9 rounded-full bg-indigo-600 text-white place-items-center font-semibold">
							{{ strtoupper(substr($googleUser->name,0,1)) }}
						</div>
					@endif
				@endif
				<form method="POST" action="{{ route('google.logout') }}" class="hidden sm:block">
					@csrf
					<button type="submit" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 focus:ring-2 focus:ring-red-400 text-white px-3 py-2 rounded-md text-xs sm:text-sm font-medium">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3-3m0 0l-3-3m3 3H3"/></svg>
						Logout
					</button>
				</form>
			</div>
		</div>

		<!-- Mobile Nav -->
		<div class="sm:hidden py-2 hidden" id="pmb-nav-menu">
			<div class="flex flex-col gap-1">
				@foreach($navLinks as $link)
					<a href="{{ $link['href'] }}" class="px-2 py-2 rounded-md text-sm {{ $link['active'] ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700' }}">
						{{ $link['label'] }}
					</a>
				@endforeach
				<div class="px-2 pt-2">
					<form method="POST" action="{{ route('google.logout') }}">
						@csrf
						<button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 focus:ring-2 focus:ring-red-400 text-white px-3 py-2 rounded-md text-sm font-medium">
							Logout
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</nav>

<script>
	// Simple mobile menu toggle
	document.addEventListener('DOMContentLoaded', function(){
		const btn = document.getElementById('pmb-nav-toggle');
		const menu = document.getElementById('pmb-nav-menu');
		if(btn && menu){ btn.addEventListener('click', ()=> menu.classList.toggle('hidden')); }
	});
	</script>
