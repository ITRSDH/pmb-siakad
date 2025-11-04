@extends('admin.layouts.app')

@section('title', 'Detail Jalur Pendaftaran')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Detail Jalur Pendaftaran</h3>
        <h6 class="op-7 mb-2">Informasi lengkap jalur pendaftaran</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('jalur-pendaftaran.edit', $jalurPendaftaran) }}" class="btn btn-warning btn-round me-2">
            <span class="btn-label"><i class="fa fa-edit"></i></span>
            Edit Data
        </a>
        <a href="{{ route('jalur-pendaftaran.index') }}" class="btn btn-label-info btn-round">
            <span class="btn-label"><i class="fa fa-arrow-left"></i></span>
            Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Information Card -->
    <div class="col-md-8">
        <div class="card card-primary card-round">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar avatar-lg me-3">
                        <div class="avatar-title bg-primary rounded-circle">
                            <i class="fas fa-route text-white fs-1"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold text-primary mb-1">{{ $jalurPendaftaran->nama_jalur }}</h3>
                        <span class="badge badge-success">Status: Aktif</span>
                    </div>
                </div>

                @if($jalurPendaftaran->deskripsi)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-2"><i class="fas fa-file-alt me-2"></i>Deskripsi</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $jalurPendaftaran->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Information Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td class="fw-bold" style="width: 200px;">
                                    <i class="fas fa-hashtag me-2 text-primary"></i>ID
                                </td>
                                <td>{{ $jalurPendaftaran->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-route me-2 text-info"></i>Nama Jalur
                                </td>
                                <td><strong>{{ $jalurPendaftaran->nama_jalur }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-calendar-plus me-2 text-success"></i>Tanggal Dibuat
                                </td>
                                <td>
                                    {{ $jalurPendaftaran->created_at->format('d F Y, H:i') }} WIB
                                    <small class="text-muted d-block">{{ $jalurPendaftaran->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">
                                    <i class="fas fa-calendar-edit me-2 text-warning"></i>Terakhir Diubah
                                </td>
                                <td>
                                    {{ $jalurPendaftaran->updated_at->format('d F Y, H:i') }} WIB
                                    <small class="text-muted d-block">{{ $jalurPendaftaran->updated_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="col-md-4">
        <div class="row">
            <!-- Edit Action -->
            <div class="col-12 mb-3">
                <div class="card card-warning card-round">
                    <div class="card-body text-center">
                        <div class="card-opening text-white">Edit Data</div>
                        <div class="card-desc">
                            <i class="fas fa-edit fa-3x text-white mb-3"></i>
                            <p class="text-white">Perbarui informasi jalur pendaftaran ini</p>
                        </div>
                        <div class="card-detail">
                            <a href="{{ route('jalur-pendaftaran.edit', $jalurPendaftaran) }}" class="btn btn-warning btn-round">
                                <i class="fa fa-edit"></i> Edit Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List All Action -->
            <div class="col-12 mb-3">
                <div class="card card-info card-round">
                    <div class="card-body text-center">
                        <div class="card-opening text-white">Daftar Semua</div>
                        <div class="card-desc">
                            <i class="fas fa-list fa-3x text-white mb-3"></i>
                            <p class="text-white">Lihat semua jalur pendaftaran yang tersedia</p>
                        </div>
                        <div class="card-detail">
                            <a href="{{ route('jalur-pendaftaran.index') }}" class="btn btn-info btn-round">
                                <i class="fa fa-list"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Action -->
            <div class="col-12 mb-3">
                <div class="card card-danger card-round">
                    <div class="card-body text-center">
                        <div class="card-opening text-white">Zona Bahaya</div>
                        <div class="card-desc">
                            <i class="fas fa-trash fa-3x text-white mb-3"></i>
                            <p class="text-white">Hapus jalur pendaftaran ini secara permanen</p>
                        </div>
                        <div class="card-detail">
                            <button type="button" class="btn btn-danger btn-round" onclick="confirmDelete()">
                                <i class="fa fa-trash"></i> Hapus Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        @if(!$jalurPendaftaran->deskripsi)
        <div class="card card-secondary card-round">
            <div class="card-body">
                <h5 class="card-title text-white"><i class="fas fa-info-circle"></i> Perhatian</h5>
                <div class="separator-solid"></div>
                <p class="text-white mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Jalur pendaftaran ini belum memiliki deskripsi. Tambahkan deskripsi untuk memberikan informasi yang lebih lengkap.
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Form for Delete -->
<form id="deleteForm" action="{{ route('jalur-pendaftaran.destroy', $jalurPendaftaran) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function confirmDelete() {
        swal({
            title: 'Hapus Data?',
            text: "Apakah Anda yakin ingin menghapus jalur pendaftaran '" + "{{ $jalurPendaftaran->nama_jalur }}" + "'?\n\nTindakan ini tidak dapat dibatalkan!",
            type: 'warning',
            buttons: {
                cancel: {
                    visible: true,
                    text: 'Batal',
                    className: 'btn btn-danger'
                },
                confirm: {
                    text: 'Ya, Hapus!',
                    className: 'btn btn-success'
                }
            }
        }).then((willDelete) => {
            if (willDelete) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>
@endpush