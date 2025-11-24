<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JalurPendaftaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jalur_pendaftaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_jalur',
        'deskripsi',
    ];

    public function biayaPendaftarans()
    {
        return $this->hasMany(BiayaPendaftaran::class, 'jalur_pendaftaran_id');
    }

    public function periodePendaftarans()
    {
        return $this->hasMany(PeriodePendaftaran::class, 'jalur_pendaftaran_id');
    }

    public function dokumenPendaftar()
    {
        return $this->belongsToMany(DokumenPendaftar::class, 'jalur_dokumen_pivot', 'jalur_pendaftaran_id', 'dokumen_pendaftar_id')
                    ->withPivot(['is_wajib', 'catatan'])
                    ->withTimestamps();
    }
}
