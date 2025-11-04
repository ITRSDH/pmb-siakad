<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class PendaftarPembayaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pendaftar_pembayaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pendaftar_id',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'bukti_pembayaran',
        'status',
        'catatan',
    ];

    // Relationships
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
