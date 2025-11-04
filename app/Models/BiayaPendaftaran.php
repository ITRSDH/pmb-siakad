<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BiayaPendaftaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'biaya_pendaftaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_biaya',
        'jumlah_biaya',
        'jalur_pendaftaran_id',
    ];

    public function jalurPendaftaran()
    {
        return $this->belongsTo(JalurPendaftaran::class, 'jalur_pendaftaran_id');
    }
}
