<?php

namespace App\Models;

use App\Models\Gelombang;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PeriodePendaftaran extends Model
{
      use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'periode_pendaftaran';

    protected $fillable = ['nama_periode', 'deskripsi', 'gelombang_id', 'jalur_pendaftaran_id', 'biaya_pendaftaran_id', 'tanggal_mulai', 'tanggal_selesai', 'jam_buka', 'jam_tutup', 'kuota', 'kuota_terisi', 'status'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relationships
    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function jalurPendaftaran(): BelongsTo
    {
        return $this->belongsTo(\App\Models\JalurPendaftaran::class);
    }

    public function biayaPendaftaran(): BelongsTo
    {
        return $this->belongsTo(\App\Models\BiayaPendaftaran::class);
    }

    public function pendaftars(): HasMany
    {
        return $this->hasMany(\App\Models\Pendaftar::class, 'periode_pendaftaran_id');
    }

    public function dokumenPendaftars(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\DokumenPendaftar::class, 'periode_pendaftaran_dokumen')
                    ->withPivot(['is_wajib', 'catatan'])
                    ->withTimestamps()
                    ->orderBy('nama_dokumen');
    }

    public function dokumenWajib(): BelongsToMany
    {
        return $this->dokumenPendaftars()->wherePivot('is_wajib', true);
    }

    public function dokumenOpsional(): BelongsToMany
    {
        return $this->dokumenPendaftars()->wherePivot('is_wajib', false);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeVisible($query)
    {
        // Backwards-compatible helper: visibility is determined by status (aktif)
        return $query->where('status', 'aktif');
    }

    public function scopeBerjalan($query)
    {
        $today = now()->startOfDay();
        return $query->whereDate('tanggal_mulai', '<=', $today)->whereDate('tanggal_selesai', '>=', $today);
    }

    public function scopeBelumSelesai($query)
    {
        $today = now()->toDateString();
        return $query->whereDate('tanggal_selesai', '>=', $today);
    }

    // Accessors & Mutators
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'aktif' => 'success',
            'nonaktif' => 'secondary',
            'draft' => 'warning',
            'selesai' => 'info',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    public function getKuotaSisaAttribute()
    {
        return max(0, $this->kuota - $this->kuota_terisi);
    }

    public function getPersentaseKuotaAttribute()
    {
        if ($this->kuota == 0) {
            return 0;
        }
        return round(($this->kuota_terisi / $this->kuota) * 100, 1);
    }

    public function getIsBerjalanAttribute()
    {
        $today = now()->startOfDay();
        return $this->tanggal_mulai->lte($today) && $this->tanggal_selesai->gte($today);
    }

    public function getIsPendingAttribute()
    {
        $today = now()->startOfDay();
        return $this->tanggal_mulai->gt($today);
    }

    public function getIsExpiredAttribute()
    {
        $today = now()->startOfDay();
        return $this->tanggal_selesai->lt($today);
    }

    // Methods
    public function updateKuotaTerisi()
    {
        $this->kuota_terisi = $this->pendaftars()->count();
        $this->save();
    }

    public function isAvailable()
    {
        // Availability is based on status, timing, and kuota only.
        return $this->status === 'aktif' && $this->is_berjalan && $this->kuota_sisa > 0;
    }

    public function getDurasiPendaftaran()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }
}
