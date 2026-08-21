@extends('admin.layouts.app')

@section('title', 'Detail Pendaftar')

@php
    $backUrl = request('from') == 'menunggu' 
        ? route('kelengkapan-pendaftar.index') 
        : url()->previous();
@endphp

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Detail Pendaftar</h3>
            <h6 class="op-7 mb-2">{{ $pendaftar->nomor_pendaftaran }} - {{ $pendaftar->nama_lengkap }}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-round">
                <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
                Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Data Pribadi -->
        <div class="col-md-6">
            <div class="card card-round">
                <div class="card-header">
                    <h4 class="card-title">Data Pribadi</h4>
                </div>
                <div class="card-body">
                    @if($pendaftar->kelengkapan)
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Nomor Pendaftaran</strong></td>
                                <td>: {{ $pendaftar->nomor_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Lengkap</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIK</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->nik }}</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->nisn ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->no_hp }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenis Kelamin</strong></td>
                                <td>: {{ ucfirst($pendaftar->kelengkapan->jenis_kelamin) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tempat, Tanggal Lahir</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->tempat_lahir }}, {{ $pendaftar->kelengkapan->tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->kelengkapan->tanggal_lahir)->format('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat Lengkap</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->alamat_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Provinsi</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->provinsi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kabupaten/Kota</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->kabupaten_kota }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kecamatan</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->kecamatan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kelurahan</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->kelurahan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Asal Sekolah</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->asal_sekolah }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tahun Lulus</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->tahun_lulus ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Ayah</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->nama_ayah }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pekerjaan Ayah</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->pekerjaan_ayah }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Ibu</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->nama_ibu }}</td>
                            </tr>
                            <tr>
                                <td><strong>Pekerjaan Ibu</strong></td>
                                <td>: {{ $pendaftar->kelengkapan->pekerjaan_ibu }}</td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> Data kelengkapan belum diisi
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Data Pendaftaran -->
        <div class="col-md-6">
            <div class="card card-round">
                <div class="card-header">
                    <h4 class="card-title">Data Pendaftaran</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Periode</strong></td>
                            <td>: {{ $pendaftar->periodePendaftaran->nama_periode ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Gelombang</strong></td>
                            <td>: {{ $pendaftar->periodePendaftaran->gelombang->nama_gelombang ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jalur Pendaftaran</strong></td>
                            <td>: {{ $pendaftar->periodePendaftaran->jalurPendaftaran->nama_jalur ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Program Studi Pilihan</strong></td>
                            <td>: {{ $pendaftar->prodi->nama_prodi ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Biaya Pendaftaran</strong></td>
                            <td>: Rp
                                {{ number_format($pendaftar->periodePendaftaran->biayaPendaftaran->jumlah_biaya ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status Pendaftaran</strong></td>
                            <td>:
                                @if ($pendaftar->status == 'draft')
                                    <span class="badge badge-warning">Draft</span>
                                @elseif($pendaftar->status == 'submitted')
                                    <span class="badge badge-info">Submitted</span>
                                @elseif($pendaftar->status == 'verified')
                                    <span class="badge badge-success">Verified</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Daftar</strong></td>
                            <td>: {{ $pendaftar->created_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Status Pembayaran -->
            <div class="card card-round mt-3">
                <div class="card-header">
                    <h4 class="card-title">Status Pembayaran</h4>
                </div>
                <div class="card-body">
                    @if ($pendaftar->payments->count() > 0)
                        @foreach ($pendaftar->payments as $payment)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="card-title mb-1">
                                                <i class="fa fa-credit-card"></i> Pembayaran #{{ $loop->iteration }}
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fa fa-calendar"></i>
                                                {{ $payment->created_at->format('d F Y H:i') }}
                                            </small>
                                        </div>
                                        <div>
                                            @if ($payment->status == 'confirmed')
                                                <span class="badge badge-success">
                                                    <i class="fa fa-check"></i> Dikonfirmasi
                                                </span>
                                            @elseif($payment->status == 'pending')
                                                <span class="badge badge-warning">
                                                    <i class="fa fa-clock"></i> Menunggu
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fa fa-times"></i> Ditolak
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        @if ($payment->bukti_pembayaran)
                                            <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank"
                                                class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i> Lihat Bukti Pembayaran
                                            </a>
                                        @else
                                            <span class="text-muted">
                                                <i class="fa fa-exclamation-triangle"></i> Bukti pembayaran belum diupload
                                            </span>
                                        @endif

                                        @if ($payment->note)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Catatan:</strong> {{ $payment->note }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            Belum ada pembayaran yang diupload
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kelengkapan Dokumen -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-round">
                <div class="card-header">
                    <h4 class="card-title">Kelengkapan Dokumen</h4>
                </div>
                <div class="card-body">
                    @if (isset($dokumenDetail) && $dokumenDetail->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dokumen</th>
                                        <th>Status Upload</th>
                                        <th>Kategori</th>
                                        <th>Tanggal Upload</th>
                                        <th>Status Kelengkapan</th>
                                        <th>Catatan Admin</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dokumenDetail as $index => $dokumen)
                                        <tr class="{{ $dokumen['is_uploaded'] ? 'table-success' : 'table-warning' }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $dokumen['nama_dokumen'] }}</strong>
                                            </td>
                                            <td>
                                                @if ($dokumen['is_uploaded'])
                                                    <span class="badge badge-success">
                                                        <i class="fa fa-check"></i> Sudah Upload
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fa fa-times"></i> Belum Upload
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dokumen['is_wajib'])
                                                    <span class="badge badge-danger">Wajib</span>
                                                @else
                                                    <span class="badge badge-info">Opsional</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dokumen['is_uploaded'] && $dokumen['uploaded_at'])
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($dokumen['uploaded_at'])->format('d M Y H:i') }}
                                                    </small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dokumen['status'] == 'submitted')
                                                    <span class="badge badge-success">Submitted</span>
                                                @elseif ($dokumen['status'] == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-warning">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $dokumen['catatan_admin'] ?? '-' }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if ($dokumen['is_uploaded'] && $dokumen['file_path'])
                                                        <a href="{{ asset('storage/' . $dokumen['file_path']) }}"
                                                            target="_blank" class="btn btn-sm btn-primary">
                                                            <i class="fa fa-eye"></i> Lihat
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Ringkasan Dokumen -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <h5><i class="fa fa-info-circle"></i> Ringkasan Kelengkapan</h5>
                                    @php
                                        $totalDokumen = $dokumenDetail->count();
                                        $dokumenUploaded = $dokumenDetail->where('is_uploaded', true)->count();
                                        $dokumenWajib = $dokumenDetail->where('is_wajib', true)->count();
                                        $dokumenWajibUploaded = $dokumenDetail
                                            ->where('is_wajib', true)
                                            ->where('is_uploaded', true)
                                            ->count();
                                        $persentase = $totalDokumen > 0 ? round(($dokumenUploaded / $totalDokumen) * 100) : 0;
                                    @endphp
                                    <ul class="mb-0">
                                        <li>Total Dokumen: {{ $dokumenUploaded }}/{{ $totalDokumen }} ({{ $persentase }}%)</li>
                                        <li>Dokumen Wajib: {{ $dokumenWajibUploaded }}/{{ $dokumenWajib }}</li>
                                        <li>Status:
                                            @if ($dokumenWajibUploaded >= $dokumenWajib && $dokumenWajib > 0)
                                                <span class="badge badge-success">Lengkap</span>
                                            @else
                                                <span class="badge badge-warning">Belum Lengkap</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-secondary">
                                    <h6>Keterangan:</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small><span class="badge badge-success">Hijau</span> = Sudah Upload</small>
                                        </div>
                                        <div class="col-6">
                                            <small><span class="badge badge-warning">Kuning</span> = Belum Upload</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            Tidak ada dokumen yang diperlukan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
