@extends('admin.layouts.app')

@section('title', 'Detail Biaya Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Detail Biaya Pendaftaran</h3>
        <h6 class="op-7 mb-2">Informasi lengkap biaya pendaftaran</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('biaya-pendaftaran.edit', $biayaPendaftaran) }}" class="btn btn-label-warning btn-round me-2">
            <span class="btn-label"><i class="fa fa-edit"></i></span>
            Edit Data
        </a>
        <a href="{{ route('biaya-pendaftaran.index') }}" class="btn btn-label-secondary btn-round">
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
                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                        {{ $biayaPendaftaran->nama_biaya }}
                    </h4>
                    <div class="ms-auto">
                        <span class="badge badge-success">ID: {{ $biayaPendaftaran->id }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Nama Biaya</label>
                            <h5 class="text-dark">{{ $biayaPendaftaran->nama_biaya }}</h5>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Jumlah Biaya</label>
                            <h4 class="text-success">
                                <i class="fas fa-rupiah-sign"></i>
                                {{ number_format($biayaPendaftaran->jumlah_biaya, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="separator-dashed"></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Jalur Pendaftaran</label>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-primary me-2">
                                    <i class="fa fa-route"></i>
                                </span>
                                <h6 class="mb-0">{{ $biayaPendaftaran->jalurPendaftaran->nama_jalur }}</h6>
                            </div>
                            @if($biayaPendaftaran->jalurPendaftaran->deskripsi)
                                <small class="text-muted">{{ $biayaPendaftaran->jalurPendaftaran->deskripsi }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Status</label>
                            <div>
                                <span class="badge badge-success">
                                    <i class="fa fa-check-circle"></i> Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator-dashed"></div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Dibuat</label>
                            <p class="mb-0">{{ $biayaPendaftaran->created_at->format('d F Y') }}</p>
                            <small class="text-muted">{{ $biayaPendaftaran->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Terakhir Diubah</label>
                            <p class="mb-0">{{ $biayaPendaftaran->updated_at->format('d F Y') }}</p>
                            <small class="text-muted">{{ $biayaPendaftaran->updated_at->format('H:i') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Selisih Waktu</label>
                            <p class="mb-0 text-info">{{ $biayaPendaftaran->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-action">
                <a href="{{ route('biaya-pendaftaran.edit', $biayaPendaftaran) }}" class="btn btn-warning">
                    <span class="btn-label"><i class="fa fa-edit"></i></span>
                    Edit Data
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <span class="btn-label"><i class="fa fa-trash"></i></span>
                    Hapus Data
                </button>
                <a href="{{ route('biaya-pendaftaran.create') }}" class="btn btn-success">
                    <span class="btn-label"><i class="fa fa-plus"></i></span>
                    Tambah Baru
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-success card-round">
            <div class="card-body">
                <div class="card-title fw-mediumbold text-white">Statistik Biaya</div>
                <div class="card-category text-white op-7">Informasi dalam angka</div>
                <div class="separator-solid"></div>
                
                <div class="d-flex justify-content-between align-items-center text-white mb-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $biayaPendaftaran->id }}</h3>
                        <small class="op-7">ID Biaya</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-hashtag fa-2x op-7"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center text-white mb-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($biayaPendaftaran->jumlah_biaya / 1000) }}K</h3>
                        <small class="op-7">Biaya (Ribuan)</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-coins fa-2x op-7"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center text-white">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $biayaPendaftaran->created_at->diffInDays($biayaPendaftaran->updated_at) }}</h3>
                        <small class="op-7">Hari Update</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-calendar-day fa-2x op-7"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-round">
            <div class="card-body">
                <div class="card-title fw-mediumbold text-white">Detail Jalur</div>
                <div class="card-category text-white op-7">{{ $biayaPendaftaran->jalurPendaftaran->nama_jalur }}</div>
                <div class="separator-solid"></div>
                
                <div class="text-white">
                    @if($biayaPendaftaran->jalurPendaftaran->deskripsi)
                        <p class="mb-3">{{ $biayaPendaftaran->jalurPendaftaran->deskripsi }}</p>
                    @else
                        <p class="mb-3 op-7">Tidak ada deskripsi tersedia</p>
                    @endif
                    
                    <div class="d-flex justify-content-between">
                        <small class="op-7">ID Jalur:</small>
                        <small class="fw-bold">{{ $biayaPendaftaran->jalur_pendaftaran_id }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-secondary card-round">
            <div class="card-body text-center">
                <div class="card-opening text-white">Aksi Cepat</div>
                <div class="card-desc">
                    <p class="text-white op-7">Kelola data biaya pendaftaran</p>
                </div>
                <div class="card-detail">
                    <div class="d-grid gap-2">
                        <a href="{{ route('biaya-pendaftaran.edit', $biayaPendaftaran) }}" class="btn btn-light btn-round btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('biaya-pendaftaran.create') }}" class="btn btn-light btn-round btn-sm">
                            <i class="fa fa-plus"></i> Tambah Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form for Delete -->
<form id="deleteForm" action="{{ route('biaya-pendaftaran.destroy', $biayaPendaftaran) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    swal({
        title: "Hapus Biaya Pendaftaran?",
        text: "Data '{{ $biayaPendaftaran->nama_biaya }}' akan dihapus secara permanen!",
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