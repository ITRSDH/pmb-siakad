@extends('admin.layouts.app')

@section('title', 'Detail Dokumen Pendaftar')

@section('content')
<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
        <h3 class="fw-bold mb-3">Detail Dokumen Pendaftar</h3>
        <h6 class="op-7 mb-2">Informasi lengkap dokumen pendaftar</h6>
    </div>
    <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ route('dokumen-pendaftar.edit', $dokumenPendaftar) }}" class="btn btn-label-warning btn-round me-2">
            <span class="btn-label"><i class="fa fa-edit"></i></span>
            Edit Data
        </a>
        <a href="{{ route('dokumen-pendaftar.index') }}" class="btn btn-label-secondary btn-round">
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
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        {{ $dokumenPendaftar->nama_dokumen }}
                    </h4>
                    <div class="ms-auto">
                        <span class="badge badge-success">ID: {{ $dokumenPendaftar->id }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Nama Dokumen</label>
                            <h5 class="text-dark">{{ $dokumenPendaftar->nama_dokumen }}</h5>
                        </div>
                    </div>
                </div>

                <div class="separator-dashed"></div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Dibuat</label>
                            <p class="mb-0">{{ $dokumenPendaftar->created_at->format('d F Y') }}</p>
                            <small class="text-muted">{{ $dokumenPendaftar->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Terakhir Diubah</label>
                            <p class="mb-0">{{ $dokumenPendaftar->updated_at->format('d F Y') }}</p>
                            <small class="text-muted">{{ $dokumenPendaftar->updated_at->format('H:i') }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fw-bold text-uppercase text-muted">Selisih Waktu</label>
                            <p class="mb-0 text-info">{{ $dokumenPendaftar->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-action">
                <a href="{{ route('dokumen-pendaftar.edit', $dokumenPendaftar) }}" class="btn btn-warning">
                    <span class="btn-label"><i class="fa fa-edit"></i></span>
                    Edit Data
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <span class="btn-label"><i class="fa fa-trash"></i></span>
                    Hapus Data
                </button>
                <a href="{{ route('dokumen-pendaftar.create') }}" class="btn btn-success">
                    <span class="btn-label"><i class="fa fa-plus"></i></span>
                    Tambah Baru
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-primary card-round">
            <div class="card-body">
                <div class="card-title fw-mediumbold text-white">Statistik Dokumen</div>
                <div class="card-category text-white op-7">Informasi dalam angka</div>
                <div class="separator-solid"></div>
                
                <div class="d-flex justify-content-between align-items-center text-white mb-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ strlen($dokumenPendaftar->nama_dokumen) }}</h3>
                        <small class="op-7">Karakter Nama</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-font fa-2x op-7"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center text-white mb-3">
                    <div>
                        <h3 class="fw-bold mb-0">{{ str_word_count($dokumenPendaftar->nama_dokumen) }}</h3>
                        <small class="op-7">Kata dalam Nama</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-hashtag fa-2x op-7"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center text-white">
                    <div>
                        <h3 class="fw-bold mb-0">{{ $dokumenPendaftar->created_at->diffInDays($dokumenPendaftar->updated_at) }}</h3>
                        <small class="op-7">Hari Update</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-calendar-day fa-2x op-7"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-success card-round">
            <div class="card-body">
                <div class="card-title fw-mediumbold text-white">Info Dokumen</div>
                <div class="card-category text-white op-7">{{ ucfirst($dokumenPendaftar->tipe_dokumen) }}</div>
                <div class="separator-solid"></div>
                
                <div class="text-white">
                    <p class="mb-3">{{ \Illuminate\Support\Str::limit($dokumenPendaftar->nama_dokumen, 30) }}</p>
                    
                    <div class="d-flex justify-content-between">
                        <small class="op-7">Tipe:</small>
                        <small class="fw-bold">{{ ucfirst($dokumenPendaftar->tipe_dokumen) }}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="op-7">Status:</small>
                        <small class="fw-bold">
                            @if($dokumenPendaftar->tipe_dokumen == 'wajib')
                                Wajib Upload
                            @else
                                Opsional
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-secondary card-round">
            <div class="card-body text-center">
                <div class="card-opening text-white">Aksi Cepat</div>
                <div class="card-desc">
                    <p class="text-white op-7">Kelola data dokumen pendaftar</p>
                </div>
                <div class="card-detail">
                    <div class="d-grid gap-2">
                        <a href="{{ route('dokumen-pendaftar.edit', $dokumenPendaftar) }}" class="btn btn-light btn-round btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('dokumen-pendaftar.create') }}" class="btn btn-light btn-round btn-sm">
                            <i class="fa fa-plus"></i> Tambah Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form for Delete -->
<form id="deleteForm" action="{{ route('dokumen-pendaftar.destroy', $dokumenPendaftar) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus dokumen "' + '{{ $dokumenPendaftar->nama_dokumen }}' + '"? Data akan dihapus secara permanen!')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush