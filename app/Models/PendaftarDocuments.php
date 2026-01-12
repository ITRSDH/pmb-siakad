<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class PendaftarDocuments extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pendaftar_document';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pendaftar_id',
        'dokumen_pendaftar_id',
        'alamat_dokumen',
        'catatan',  
        'status_dokumen'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Route model binding menggunakan UUID
    public function getRouteKeyName()
    {
        return 'id';
    }

    // Relationships
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function dokumenPendaftar()
    {
        return $this->belongsTo(\App\Models\DokumenPendaftar::class);
    }
}
