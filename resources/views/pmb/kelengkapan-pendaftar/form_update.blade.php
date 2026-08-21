@extends('pmb.layouts.app')

@section('title', 'Perbaiki Data Kelengkapan Pendaftar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Perbaiki Data Kelengkapan Pendaftar</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Perbaiki data Anda berdasarkan catatan admin</p>
            </div>
            <a href="{{ route('kelengkapan-pendaftar.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    @if($kelengkapan->status === 'rejected' && $kelengkapan->catatan_admin)
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Catatan Admin</h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        {{ $kelengkapan->catatan_admin }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Terjadi Kesalahan</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Progress Steps -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress</span>
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400" id="progress-text">25%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" id="progress-bar" style="width: 25%"></div>
                </div>
            </div>
            <div class="flex justify-between">
                <div class="text-center step-indicator flex-1" data-step="1">
                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-600 text-white text-sm font-medium step-badge">1</div>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Biodata</p>
                </div>
                <div class="text-center step-indicator flex-1" data-step="2">
                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm font-medium step-badge">2</div>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Alamat</p>
                </div>
                <div class="text-center step-indicator flex-1" data-step="3">
                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm font-medium step-badge">3</div>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Pendidikan</p>
                </div>
                <div class="text-center step-indicator flex-1" data-step="4">
                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm font-medium step-badge">4</div>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Dokumen</p>
                </div>
                <div class="text-center step-indicator flex-1" data-step="5">
                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-sm font-medium step-badge">5</div>
                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Orang Tua</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form action="{{ route('kelengkapan-pendaftar.update', $kelengkapan->id) }}"
                  method="POST"
                  enctype="multipart/form-data" id="kelengkapan-form">
                @csrf
                @method('PUT')

                <!-- Step 1: Biodata -->
                <div class="form-step active" data-step="1">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">1. Biodata</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('nama_lengkap', $kelengkapan->nama_lengkap) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" maxlength="16" required value="{{ old('nik', $kelengkapan->nik) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NISN <span class="text-red-500">*</span></label>
                            <input type="text" name="nisn" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" maxlength="10" required value="{{ old('nisn', $kelengkapan->nisn) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('tempat_lahir', $kelengkapan->tempat_lahir) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('tanggal_lahir', $kelengkapan->tanggal_lahir) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">Pilih</option>
                                <option value="L" {{ old('jenis_kelamin', $kelengkapan->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $kelengkapan->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" maxlength="15" required value="{{ old('no_hp', $kelengkapan->no_hp) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('email', $kelengkapan->email) }}">
                        </div>
                    </div>
                </div>

                <!-- Step 2: Alamat -->
                <div class="form-step hidden" data-step="2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">2. Alamat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat_lengkap" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" rows="3" required>{{ old('alamat_lengkap', $kelengkapan->alamat_lengkap) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provinsi <span class="text-red-500">*</span></label>
                            <input type="text" name="provinsi" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('provinsi', $kelengkapan->provinsi) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <input type="text" name="kabupaten_kota" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('kabupaten_kota', $kelengkapan->kabupaten_kota) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" name="kecamatan" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('kecamatan', $kelengkapan->kecamatan) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelurahan <span class="text-red-500">*</span></label>
                            <input type="text" name="kelurahan" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('kelurahan', $kelengkapan->kelurahan) }}">
                        </div>
                    </div>
                </div>

                <!-- Step 3: Pendidikan -->
                <div class="form-step hidden" data-step="3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">3. Pendidikan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asal Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="asal_sekolah" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('asal_sekolah', $kelengkapan->asal_sekolah) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Lulus <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun_lulus" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="2000" max="2030" required value="{{ old('tahun_lulus', $kelengkapan->tahun_lulus) }}">
                        </div>
                    </div>
                </div>

                <!-- Step 4: Dokumen -->
                <div class="form-step hidden" data-step="4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">4. Dokumen</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pas Foto</label>
                            <input type="file" name="pas_foto" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-200" accept="image/jpeg,image/png,image/jpg">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPEG, PNG, JPG. Max: 2MB</p>
                            @if($kelengkapan->pas_foto)
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">File saat ini: <a href="{{ asset('storage/' . $kelengkapan->pas_foto) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">Lihat file</a></p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">KTP</label>
                            <input type="file" name="ktp" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-200" accept="image/jpeg,image/png,image/jpg,application/pdf">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPEG, PNG, JPG, PDF. Max: 2MB</p>
                            @if($kelengkapan->ktp)
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">File saat ini: <a href="{{ asset('storage/' . $kelengkapan->ktp) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">Lihat file</a></p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">KK</label>
                            <input type="file" name="kk" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-200" accept="image/jpeg,image/png,image/jpg,application/pdf">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPEG, PNG, JPG, PDF. Max: 2MB</p>
                            @if($kelengkapan->kk)
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">File saat ini: <a href="{{ asset('storage/' . $kelengkapan->kk) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">Lihat file</a></p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ijazah/SKL</label>
                            <input type="file" name="ijazah_skl" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-200" accept="image/jpeg,image/png,image/jpg,application/pdf">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPEG, PNG, JPG, PDF. Max: 2MB</p>
                            @if($kelengkapan->ijazah_skl)
                                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">File saat ini: <a href="{{ asset('storage/' . $kelengkapan->ijazah_skl) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500">Lihat file</a></p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 5: Orang Tua -->
                <div class="form-step hidden" data-step="5">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">5. Orang Tua</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ayah" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('nama_ayah', $kelengkapan->nama_ayah) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pekerjaan Ayah <span class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan_ayah" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('pekerjaan_ayah', $kelengkapan->pekerjaan_ayah) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_ibu" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('nama_ibu', $kelengkapan->nama_ibu) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pekerjaan Ibu <span class="text-red-500">*</span></label>
                            <input type="text" name="pekerjaan_ibu" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required value="{{ old('pekerjaan_ibu', $kelengkapan->pekerjaan_ibu) }}">
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700" id="prev-btn" style="display: none;">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-auto" id="next-btn">
                        Next
                        <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 ml-auto" id="submit-btn" style="display: none;">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Perbaikan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;
    const formSteps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step-indicator');
    const stepBadges = document.querySelectorAll('.step-badge');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');

    function updateStep() {
        // Hide all steps
        formSteps.forEach(step => {
            step.classList.add('hidden');
            step.classList.remove('active');
        });
        
        // Show current step
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        currentStepElement.classList.remove('hidden');
        currentStepElement.classList.add('active');

        // Update progress bar
        const progress = Math.round((currentStep / totalSteps) * 100);
        progressBar.style.width = progress + '%';
        progressText.textContent = progress + '%';

        // Update step indicators
        stepIndicators.forEach((indicator, index) => {
            const badge = stepBadges[index];
            if (index + 1 <= currentStep) {
                badge.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
                badge.classList.add('bg-indigo-600', 'text-white');
            } else {
                badge.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-600', 'dark:text-gray-400');
                badge.classList.remove('bg-indigo-600', 'text-white');
            }
        });

        // Update buttons
        prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-flex';
        
        if (currentStep === totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-flex';
        } else {
            nextBtn.style.display = 'inline-flex';
            submitBtn.style.display = 'none';
        }
    }

    nextBtn.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStep();
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateStep();
        }
    });

    // Initialize
    updateStep();
});
</script>
@endsection
