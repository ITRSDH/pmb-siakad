@extends('pmb.layouts.app')

@section('title', 'Detail Kelengkapan Pendaftar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Detail Kelengkapan Pendaftar</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Lihat data kelengkapan yang telah Anda submit</p>
            </div>
            <a href="{{ route('pmb.kelengkapan-pendaftar.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Status -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Status Verifikasi</h3>
            @php
                $statusBg = match($kelengkapan->status) {
                    'draft' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800',
                    'submitted' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
                    'verified' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800',
                    'rejected' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
                    default => 'bg-gray-50 dark:bg-gray-900/20 border-gray-200 dark:border-gray-800'
                };
                $statusText = match($kelengkapan->status) {
                    'draft' => 'Draft',
                    'submitted' => 'Menunggu Verifikasi',
                    'verified' => 'Terverifikasi',
                    'rejected' => 'Ditolak',
                    default => 'Unknown'
                };
                $statusTextColor = match($kelengkapan->status) {
                    'draft' => 'text-yellow-800 dark:text-yellow-200',
                    'submitted' => 'text-blue-800 dark:text-blue-200',
                    'verified' => 'text-green-800 dark:text-green-200',
                    'rejected' => 'text-red-800 dark:text-red-200',
                    default => 'text-gray-800 dark:text-gray-200'
                };
            @endphp
            <div class="{{ $statusBg }} border rounded-md p-4">
                <div class="flex items-center">
                    @if($kelengkapan->status === 'verified')
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($kelengkapan->status === 'rejected')
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($kelengkapan->status === 'submitted')
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    <span class="ml-3 text-sm font-medium {{ $statusTextColor }}">{{ $statusText }}</span>
                </div>
                
                @if($kelengkapan->status === 'rejected' && $kelengkapan->catatan_admin)
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Admin:</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $kelengkapan->catatan_admin }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Biodata -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">1. Biodata</h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->nama_lengkap }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NIK</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->nik }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NISN</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->nisn }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempat Lahir</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->tempat_lahir }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($kelengkapan->tanggal_lahir)->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kelamin</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. HP</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->no_hp }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->email }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Alamat -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">2. Alamat</h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat Lengkap</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->alamat_lengkap }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Provinsi</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->provinsi }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kabupaten/Kota</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->kabupaten_kota }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kecamatan</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->kecamatan }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kelurahan</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->kelurahan }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Pendidikan -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">3. Pendidikan</h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Asal Sekolah</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->asal_sekolah }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tahun Lulus</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->tahun_lulus }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Dokumen -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">4. Dokumen</h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pas Foto</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($kelengkapan->pas_foto)
                            <a href="{{ asset('storage/' . $kelengkapan->pas_foto) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">Belum diupload</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">KTP</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($kelengkapan->ktp)
                            <a href="{{ asset('storage/' . $kelengkapan->ktp) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">Belum diupload</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">KK</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($kelengkapan->kk)
                            <a href="{{ asset('storage/' . $kelengkapan->kk) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">Belum diupload</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ijazah/SKL</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($kelengkapan->ijazah_skl)
                            <a href="{{ asset('storage/' . $kelengkapan->ijazah_skl) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">Belum diupload</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Orang Tua -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">5. Orang Tua</h3>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Ayah</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->nama_ayah }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pekerjaan Ayah</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->pekerjaan_ayah }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Ibu</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->nama_ibu }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pekerjaan Ibu</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $kelengkapan->pekerjaan_ibu }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
