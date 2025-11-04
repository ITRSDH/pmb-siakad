
@extends('admin.layouts.app')

@section('title', 'Detail Pendaftar')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
	<div>
		<h3 class="fw-bold mb-3">Detail Pendaftar</h3>
		<h6 class="op-7 mb-2">Informasi lengkap pendaftar mahasiswa baru</h6>
	</div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('pendaftar.index') }}" class="btn btn-label-secondary btn-round">
            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
            Kembali
        </a>
    </div>
</div>

<div class="row">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header">
				<div class="d-flex align-items-center">
					<h4 class="card-title">
						<i class="fas fa-user-graduate text-primary me-2"></i>
						{{ $pendaftar->nama_lengkap }}
					</h4>
					<div class="ms-auto">
						<span class="badge badge-info">No. Pendaftaran: {{ $pendaftar->nomor_pendaftaran }}</span>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Nama Lengkap</label>
							<h5 class="text-dark">{{ $pendaftar->nama_lengkap }}</h5>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Email</label>
							<h6 class="text-dark">{{ $pendaftar->email }}</h6>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">No HP</label>
							<h6 class="text-dark">{{ $pendaftar->no_hp }}</h6>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Jenis Kelamin</label>
							<h6 class="text-dark">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</h6>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Tanggal Lahir</label>
							<h6 class="text-dark">{{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d F Y') : '-' }}</h6>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Alamat</label>
							<h6 class="text-dark">{{ $pendaftar->alamat }}</h6>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Periode Pendaftaran</label>
							<h6 class="text-dark">{{ optional($pendaftar->periodePendaftaran)->nama_periode ?? '-' }}</h6>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Jalur Pendaftaran</label>
							<h6 class="text-dark">{{ optional(optional($pendaftar->periodePendaftaran)->jalurPendaftaran)->nama_jalur ?? '-' }}</h6>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Status Pendaftaran</label>
							<span class="badge badge-{{ $pendaftar->status == 'verified' ? 'success' : ($pendaftar->status == 'submitted' ? 'info' : ($pendaftar->status == 'draft' ? 'warning' : 'danger')) }}">
								{{ ucfirst($pendaftar->status) }}
							</span>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Status Pembayaran</label>
							@php
								$latest = $pendaftar->payments->sortByDesc('created_at')->first();
								$statusBayar = $latest?->status === 'confirmed' ? 'Lunas' : ($latest?->status === 'pending' ? 'Menunggu' : 'Belum');
							@endphp
							<span class="badge badge-{{ $statusBayar == 'Lunas' ? 'success' : ($statusBayar == 'Menunggu' ? 'warning' : 'secondary') }}">
								{{ $statusBayar }}
							</span>
						</div>
						<div class="form-group">
							<label class="fw-bold text-uppercase text-muted">Tanggal Daftar</label>
							<h6 class="text-dark">{{ $pendaftar->created_at ? $pendaftar->created_at->format('d F Y H:i') : '-' }}</h6>
						</div>
					</div>
				</div>

				<div class="separator-dashed"></div>

				<div class="row">
					<div class="col-md-12">
						<label class="fw-bold text-uppercase text-muted">Dokumen Terupload</label>
						<ul class="list-group mb-3">
							@forelse($pendaftar->documents as $doc)
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span>{{ $doc->alamat_dokumen }}</span>
									<a href="{{ asset('storage/' . $doc->alamat_dokumen) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
								</li>
							@empty
								<li class="list-group-item text-muted">Belum ada dokumen diupload</li>
							@endforelse
						</ul>
					</div>
				</div>

				<div class="separator-dashed"></div>

				<div class="row">
					<div class="col-md-12">
						<label class="fw-bold text-uppercase text-muted">Riwayat Pembayaran</label>
						<ul class="list-group">
							@forelse($pendaftar->payments->sortByDesc('created_at') as $pay)
								<li class="list-group-item d-flex justify-content-between align-items-center">
									<span>
										{{ $pay->tanggal_pembayaran ? \Carbon\Carbon::parse($pay->tanggal_pembayaran)->format('d-m-Y') : '-' }}
										| <span class="badge badge-{{ $pay->status == 'confirmed' ? 'success' : ($pay->status == 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($pay->status) }}</span>
										@if($pay->catatan)
											<span class="text-muted">({{ $pay->catatan }})</span>
										@endif
									</span>
									<a href="{{ asset('storage/' . $pay->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>
								</li>
							@empty
								<li class="list-group-item text-muted">Belum ada pembayaran</li>
							@endforelse
						</ul>
					</div>
				</div>
			</div>

			<!-- Card action dihilangkan -->
		</div>
	</div>

	<div class="col-md-4">
		<div class="card card-success card-round">
			<div class="card-body">
				<div class="card-title fw-mediumbold text-white">Statistik Pendaftar</div>
				<div class="card-category text-white op-7">Informasi dalam angka</div>
				<div class="separator-solid"></div>
				<div class="d-flex justify-content-between align-items-center text-white mb-3">
					<div>
						<h3 class="fw-bold mb-0">{{ $pendaftar->id }}</h3>
						<small class="op-7">ID Pendaftar</small>
					</div>
					<div class="text-end">
						<i class="fas fa-hashtag fa-2x op-7"></i>
					</div>
				</div>
				<div class="d-flex justify-content-between align-items-center text-white mb-3">
					<div>
						<h3 class="fw-bold mb-0">{{ $pendaftar->umur ?? '-' }}</h3>
						<small class="op-7">Umur</small>
					</div>
					<div class="text-end">
						<i class="fas fa-birthday-cake fa-2x op-7"></i>
					</div>
				</div>
				<div class="d-flex justify-content-between align-items-center text-white">
					<div>
						<h3 class="fw-bold mb-0">{{ $pendaftar->documents->count() }}</h3>
						<small class="op-7">Dokumen</small>
					</div>
					<div class="text-end">
						<i class="fas fa-file-alt fa-2x op-7"></i>
					</div>
				</div>
			</div>
		</div>
		<!-- Card aksi cepat dihilangkan -->
	</div>
</div>

<!-- Form delete dihilangkan -->
@endsection

<!-- Script aksi dihilangkan -->
