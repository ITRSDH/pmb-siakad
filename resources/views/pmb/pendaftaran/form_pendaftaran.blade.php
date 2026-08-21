@extends('pmb.layouts.app')

@section('title', 'Form Pendaftaran')

@section('content')
<div x-data="{ loading: false, sumberInformasi: '{{ old('sumber_informasi') }}' }" class="relative">

    <!-- Overlay Loading -->
    <div 
        x-show="loading"
        x-transition.opacity
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="flex flex-col items-center">
            <svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-40" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-90" fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
            <p class="text-white mt-4 text-sm">Menyimpan data pendaftaran...</p>
        </div>
    </div>
    <!-- END Overlay -->

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Form Pendaftaran - {{ $periode->nama_periode }}
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Silakan lengkapi data diri Anda. Data dapat disimpan sebagai draft terlebih dahulu.
            </p>

            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                <span class="text-red-500">*</span> Field wajib diisi
            </p>

            <form method="POST" action="{{ route('pmb.daftar.store', $periode->id) }}" 
                  enctype="multipart/form-data"
                  class="mt-6 space-y-4"
                  @submit="loading = true">

                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Program Studi <span class="text-red-500">*</span>
                    </label>
                    <select name="prodi_id" required
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                            </option>
                        @endforeach
                    </select>
                    @error('prodi_id')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm 
                               focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm 
                               dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    @error('nama_lengkap')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            No. HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                            minlength="10" maxlength="15"
                            pattern="[0-9]{10,15}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            placeholder="Masukkan nomor HP (10-15 digit)" />
                        @error('no_hp')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Nomor HP harus 10-15 digit angka</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Sumber Informasi <span class="text-red-500">*</span>
                    </label>
                    @error('sumber_informasi')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <select name="sumber_informasi" required
                        x-model="sumberInformasi"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">-- Pilih --</option>
                        <option value="Media-Sosial" {{ old('sumber_informasi') == 'Media-Sosial' ? 'selected' : '' }}>Media Sosial(Instagram, Telegram)</option>
                        <option value="Teman-atau-Saudara" {{ old('sumber_informasi') == 'Teman-atau-Saudara' ? 'selected' : '' }}>Teman atau Saudara</option>
                        <option value="Sekolah-Asal" {{ old('sumber_informasi') == 'Sekolah-Asal' ? 'selected' : '' }}>Sekolah Asal</option>
                        <option value="Website" {{ old('sumber_informasi') == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Spanduk-atau-Poster" {{ old('sumber_informasi') == 'Spanduk-atau-Poster' ? 'selected' : '' }}>Spanduk atau Poster</option>
                        <option value="Edufair-atau-Expo" {{ old('sumber_informasi') == 'Edufair-atau-Expo' ? 'selected' : '' }}>Edufair atau Expo</option>
                        <option value="Lainnya" {{ old('sumber_informasi') == 'Lainnya' ? 'selected' : '' }}>Lainnya(Alumni, Karyawan, dll)</option>
                    </select>
                </div>

                <div x-show="sumberInformasi === 'Lainnya'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Jelaskan Sumber Informasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="lainnya" value="{{ old('lainnya') }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                        placeholder="Contoh: Alumni, Karyawan, dll" />
                    @error('lainnya')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('pmb.pendaftaran.index') }}" 
                       class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">
                        Batal
                    </a>

                    <button type="submit" 
                        class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                        Simpan & Lanjut
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
