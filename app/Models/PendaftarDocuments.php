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
        'alamat_dokumen',
        'catatan',  
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
