<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>@yield('title', 'PMB - SIAKAD')</title>
	<meta name="color-scheme" content="light dark" />
	{{-- Use Vite-built assets (Tailwind is compiled from resources/css/app.css).
	     Replacing CDN with @vite ensures project Tailwind config, plugins and
	     any custom CSS are applied during dev and production builds. --}}
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@stack('head')
	<!-- You can add shared styles/scripts for all PMB pages here -->
	<style>
		/* Shared PMB tweaks can go here */
	</style>
    
</head>
<body class="bg-gray-50 dark:bg-gray-900">
	@include('pmb.layouts.header')

	<div class="min-h-screen">
		@yield('content')
	</div>

	@include('pmb.layouts.footer')

	@stack('scripts')
</body>
</html>
