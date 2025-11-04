@extends('admin.layouts.app')

@section('title', 'Detail Periode Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Detail Periode Pendaftaran</h3>
        <h6 class="op-7 mb-2">Informasi lengkap periode pendaftaran</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('periode-pendaftaran.edit', $periodePendaftaran) }}" class="btn btn-label-warning btn-round me-2">
            <span class="btn-label"><i class="fa fa-edit"></i></span>
            Edit Data
        </a>
        <a href="{{ route('periode-pendaftaran.index') }}" class="btn btn-label-secondary btn-round">
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
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        {{ $periodePendaftaran->nama_periode }}
                    </h4>
                    <div class="ms-auto">
                        <span class="badge badge-primary">ID: {{ $periodePendaftaran->id }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Informasi Utama -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Nama Periode</label>
                            <h5 class="text-dark">{{ $periodePendaftaran->nama_periode }}</h5>
                            @if($periodePendaftaran->deskripsi)
                                <p class="text-muted">{{ $periodePendaftaran->deskripsi }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Status Periode</label>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-{{ $periodePendaftaran->status_badge }} me-2">
                                    @if($periodePendaftaran->status === 'aktif')
                                        <i class="fa fa-check-circle me-1"></i>
                                    @elseif($periodePendaftaran->status === 'draft')
                                        <i class="fa fa-edit me-1"></i>
                                    @elseif($periodePendaftaran->status === 'selesai')
                                        <i class="fa fa-flag-checkered me-1"></i>
                                    @else
                                        <i class="fa fa-times-circle me-1"></i>
                                    @endif
                                    {{ ucfirst($periodePendaftaran->status) }}
                                </span>
                                
                                @if($periodePendaftaran->is_berjalan)
                                    <span class="badge badge-success">
                                        <i class="fa fa-play me-1"></i>Sedang Berjalan
                                    </span>
                                @elseif($periodePendaftaran->is_pending)
                                    <span class="badge badge-warning">
                                        <i class="fa fa-clock me-1"></i>Belum Dimulai
                                    </span>
                                @elseif($periodePendaftaran->is_expired)
                                    <span class="badge badge-secondary">
                                        <i class="fa fa-stop me-1"></i>Telah Berakhir
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-dashed"></div>

                <!-- Informasi Master Data -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Gelombang</label>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-info me-2">
                                    <i class="fa fa-water"></i>
                                </span>
                                <div>
                                    <h6 class="mb-0">{{ $periodePendaftaran->gelombang->nama_gelombang }}</h6>
                                    @if($periodePendaftaran->gelombang->deskripsi)
                                        <small class="text-muted">{{ $periodePendaftaran->gelombang->deskripsi }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Jalur Pendaftaran</label>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-primary me-2">
                                    <i class="fa fa-route"></i>
                                </span>
                                <div>
                                    <h6 class="mb-0">{{ $periodePendaftaran->jalurPendaftaran->nama_jalur }}</h6>
                                    @if($periodePendaftaran->jalurPendaftaran->deskripsi)
                                        <small class="text-muted">{{ $periodePendaftaran->jalurPendaftaran->deskripsi }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Biaya Pendaftaran</label>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-success me-2">
                                    <i class="fa fa-money-bill-wave"></i>
                                </span>
                                <div>
                                    <h6 class="mb-0">{{ $periodePendaftaran->biayaPendaftaran->nama_biaya }}</h6>
                                    <h5 class="text-success mb-0">
                                        <i class="fas fa-rupiah-sign"></i>
                                        {{ number_format($periodePendaftaran->biayaPendaftaran->jumlah_biaya, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-dashed"></div>

                <!-- Timeline & Kuota -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Periode Waktu</label>
                            <div class="timeline-wrapper">
                                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                                    <div class="text-center">
                                        <div class="fw-bold text-success">{{ $periodePendaftaran->tanggal_mulai->format('d') }}</div>
                                        <small class="text-muted">{{ $periodePendaftaran->tanggal_mulai->format('M Y') }}</small>
                                        <div><small class="text-success fw-bold">MULAI</small></div>
                                    </div>
                                    <div class="flex-grow-1 mx-3">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                                        </div>
                                        <div class="text-center mt-1">
                                            <small class="text-muted">{{ $periodePendaftaran->getDurasiPendaftaran() }} hari</small>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <div class="fw-bold text-danger">{{ $periodePendaftaran->tanggal_selesai->format('d') }}</div>
                                        <small class="text-muted">{{ $periodePendaftaran->tanggal_selesai->format('M Y') }}</small>
                                        <div><small class="text-danger fw-bold">SELESAI</small></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Kuota Pendaftar</label>
                            <div class="kuota-wrapper bg-light p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Kuota Terisi</span>
                                    <span class="fw-bold text-primary">{{ $periodePendaftaran->kuota_terisi }}/{{ $periodePendaftaran->kuota }}</span>
                                </div>
                                <div class="progress progress-lg mb-2">
                                    <div class="progress-bar bg-{{ $periodePendaftaran->persentase_kuota >= 80 ? 'danger' : ($periodePendaftaran->persentase_kuota >= 60 ? 'warning' : 'success') }}" 
                                         role="progressbar" 
                                         style="width: {{ $periodePendaftaran->persentase_kuota }}%" 
                                         aria-valuenow="{{ $periodePendaftaran->persentase_kuota }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">Persentase: {{ $periodePendaftaran->persentase_kuota }}%</small>
                                    <small class="text-muted">Sisa: {{ $periodePendaftaran->kuota_sisa }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-dashed"></div>

                <!-- Informasi Waktu -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Dibuat</label>
                            <p class="mb-0">{{ $periodePendaftaran->created_at->format('d F Y') }}</p>
                            <small class="text-muted">{{ $periodePendaftaran->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Terakhir Diubah</label>
                            <p class="mb-0">{{ $periodePendaftaran->updated_at->format('d F Y') }}</p>
                            <small class="text-muted">{{ $periodePendaftaran->updated_at->format('H:i') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Update Terakhir</label>
                            <p class="mb-0 text-info">{{ $periodePendaftaran->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-action">
                <a href="{{ route('periode-pendaftaran.edit', $periodePendaftaran) }}" class="btn btn-warning">
                    <span class="btn-label"><i class="fa fa-edit"></i></span>
                    Edit Data
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <span class="btn-label"><i class="fa fa-trash"></i></span>
                    Hapus Data
                </button>
                <a href="{{ route('periode-pendaftaran.create') }}" class="btn btn-success">
                    <span class="btn-label"><i class="fa fa-plus"></i></span>
                    Tambah Baru
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card card-{{ $periodePendaftaran->status_badge }} card-round">
            <div class="card-body">
                <div class="card-title fw-mediumbold text-white">Status Periode</div>
                <div class="card-category text-white op-7">{{ $periodePendaftaran->nama_periode }}</div>
                <div class="separator-solid"></div>
                
                <div class="d-flex justify-content-between align-items-center text-white mb-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ ucfirst($periodePendaftaran->status) }}</h3>
                        <small class="op-7">Status Saat Ini</small>
                    </div>
                    <div class="text-end">
                        @if($periodePendaftaran->status === 'aktif')
                            <i class="fas fa-check-circle fa-2x op-7"></i>
                        @elseif($periodePendaftaran->status === 'draft')
                            <i class="fas fa-edit fa-2x op-7"></i>
                        @elseif($periodePendaftaran->status === 'selesai')
                            <i class="fas fa-flag-checkered fa-2x op-7"></i>
                        @else
                            <i class="fas fa-times-circle fa-2x op-7"></i>
                        @endif
                    </div>
                </div>

                @if($periodePendaftaran->is_berjalan)
                    <p class="text-white mb-0">
                        <i class="fas fa-play me-2"></i>
                        Periode sedang berjalan
                    </p>
                @elseif($periodePendaftaran->is_pending)
                    <p class="text-white mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Periode belum dimulai
                    </p>
                @elseif($periodePendaftaran->is_expired)
                    <p class="text-white mb-0">
                        <i class="fas fa-stop me-2"></i>
                        Periode telah berakhir
                    </p>
                @endif
            </div>
        </div>

        <!-- Statistik Card -->
        <div class="card card-info card-round">
            <div class="card-body">
                <div class="card-title fw-mediumbold text-white">Statistik</div>
                <div class="card-category text-white op-7">Data pendaftar dan kuota</div>
                <div class="separator-solid"></div>
                
                <div class="d-flex justify-content-between align-items-center text-white mb-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $periodePendaftaran->kuota_terisi }}</h3>
                        <small class="op-7">Pendaftar Terdaftar</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-users fa-2x op-7"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center text-white mb-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $periodePendaftaran->kuota_sisa }}</h3>
                        <small class="op-7">Kuota Tersisa</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-user-plus fa-2x op-7"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center text-white">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $periodePendaftaran->persentase_kuota }}%</h3>
                        <small class="op-7">Persentase Terisi</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-chart-pie fa-2x op-7"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card card-secondary card-round">
            <div class="card-body text-center">
                <div class="card-opening text-white">Aksi Cepat</div>
                <div class="card-desc">
                    <p class="text-white op-7">Kelola periode pendaftaran</p>
                </div>
                <div class="card-detail">
                    <div class="d-grid gap-2">
                        <a href="{{ route('periode-pendaftaran.edit', $periodePendaftaran) }}" class="btn btn-light btn-round btn-sm">
                            <i class="fa fa-edit"></i> Edit Periode
                        </a>
                        <a href="{{ route('periode-pendaftaran.create') }}" class="btn btn-light btn-round btn-sm">
                            <i class="fa fa-plus"></i> Tambah Periode
                        </a>
                        @if($periodePendaftaran->kuota_terisi == 0)
                            <button type="button" class="btn btn-danger btn-round btn-sm" onclick="confirmDelete()">
                                <i class="fa fa-trash"></i> Hapus Periode
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form for Delete -->
<form id="deleteForm" action="{{ route('periode-pendaftaran.destroy', $periodePendaftaran) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    swal({
        title: "Hapus Periode Pendaftaran?",
        text: "Data '{{ $periodePendaftaran->nama_periode }}' akan dihapus secara permanen!",
        type: "warning",
        buttons: {
            cancel: {
                visible: true,
                text: "Batal",
                className: "btn btn-danger"
            },
            confirm: {
                text: "Ya, Hapus!",
                className: "btn btn-success"
            }
        }
    }).then((willDelete) => {
        if (willDelete) {
            document.getElementById('deleteForm').submit();
            swal("Data berhasil dihapus!", {
                icon: "success",
                buttons: false,
                timer: 2000,
            });
        }
    });
}
</script>
@endpush