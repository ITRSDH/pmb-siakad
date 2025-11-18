<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DokumenPendaftar extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'dokumen_pendaftar';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_dokumen',
        'tipe_dokumen',
    ];

    // Relationships
    public function periodePendaftarans(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\PeriodePendaftaran::class, 'periode_pendaftaran_dokumen')
                    ->withPivot(['is_wajib', 'catatan'])
                    ->withTimestamps()
                    ->orderBy('nama_periode');
    }

    // Scopes
    public function scopeWajib($query)
    {
        return $query->where('tipe_dokumen', 'wajib');
    }

    public function scopeOpsional($query)
    {
        return $query->where('tipe_dokumen', 'opsional');
    }
}
