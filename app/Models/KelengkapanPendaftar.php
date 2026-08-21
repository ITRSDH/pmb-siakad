<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KelengkapanPendaftar extends Model
{
    use HasUuids;

    protected $table = 'kelengkapan_pendaftar';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'pendaftar_id',
        'nama_lengkap',
        'nik',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp',
        'email',
        'alamat_lengkap',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'kelurahan',
        'asal_sekolah',
        'tahun_lulus',
        'pas_foto',
        'ktp',
        'kk',
        'ijazah_skl',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'status',
        'catatan_admin',
    ];
    
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
