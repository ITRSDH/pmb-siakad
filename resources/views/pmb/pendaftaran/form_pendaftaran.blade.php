@extends('pmb.layouts.app')

@section('title', 'Form Pendaftaran')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Form Pendaftaran -
                {{ $periode->nama_periode }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan lengkapi data diri Anda. Data dapat disimpan
                sebagai draft terlebih dahulu.</p>
            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                <span class="text-red-500">*</span> Field wajib diisi
            </p>

            <form method="POST" action="{{ route('pmb.daftar.store', $periode->id) }}" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Program Studi <span class="text-red-500">*</span></label>
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    @error('nama_lengkap')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik') }}"
                            maxlength="16"
                            pattern="[0-9]{16}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            placeholder="Masukkan 16 digit NIK" />
                        @error('nik')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">NIK harus 16 digit angka</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user?->email) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                        @error('email')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">No. HP <span class="text-red-500">*</span></label>
                        <input type="tel" name="no_hp" value="{{ old('no_hp') }}"
                            minlength="10" maxlength="15"
                            pattern="[0-9]{10,15}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);"
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                            required
                            placeholder="Masukkan nomor HP (10-15 digit)" />
                        @error('no_hp')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Nomor HP harus 10-15 digit angka</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required
                            class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    @error('tanggal_lahir')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                    @error('pendidikan_terakhir')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <select name="pendidikan_terakhir" required
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">-- Pilih --</option>
                        <option value="SMA" {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="SMK" {{ old('pendidikan_terakhir') == 'SMK' ? 'selected' : '' }}>SMK</option>
                        <option value="MA" {{ old('pendidikan_terakhir') == 'MA' ? 'selected' : '' }}>MA</option>
                        <option value="PAKET C" {{ old('pendidikan_terakhir') == 'PAKET C' ? 'selected' : '' }}>PAKET C</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
                    @error('asal_sekolah')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sumber Informasi <span class="text-red-500">*</span></label>
                    @error('sumber_informasi')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <select name="sumber_informasi" required
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                        <option value="">-- Pilih --</option>
                        <option value="Guru BK" {{ old('sumber_informasi') == 'Guru BK' ? 'selected' : '' }}>Guru BK</option>
                        <option value="Website" {{ old('sumber_informasi') == 'Website' ? 'selected' : '' }}>Website</option>
                        <option value="Telegram" {{ old('sumber_informasi') == 'Telegram' ? 'selected' : '' }}>Telegram</option>
                    </select>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('pmb.pendaftaran.index') }}" class="text-sm text-gray-600 dark:text-gray-300">Batal</a>
                    <button type="submit" class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">Simpan & Lanjut</button>
                </div>
            </form>
        </div>
    </div>
@endsection
