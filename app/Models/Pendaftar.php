<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pendaftar extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'pendaftar';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            if (empty($model->nomor_pendaftaran)) {
                $model->nomor_pendaftaran = self::generateNomorPendaftaran($model->periode_pendaftaran_id ?? null);
            }
        });
    }

    protected $fillable = [
        'nomor_pendaftaran',
        'user_id',
        'google_user_id',
        'periode_pendaftaran_id',
        'prodi_id',
        'nama_lengkap',
        'nik',
        'email',
        'no_hp',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'pendidikan_terakhir',
        'status',
        'asal_sekolah',
        'asal_info',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'reviewed_at' => 'datetime',
        'meta' => 'array',
    ];

    // Relationships
    public function periodePendaftaran()
    {
        return $this->belongsTo(\App\Models\PeriodePendaftaran::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function googleUser()
    {
        return $this->belongsTo(\App\Models\GoogleUser::class);
    }

    public function prodi()
    {
        return $this->belongsTo(\App\Models\Prodi::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewed_by');
    }

    // Documents uploaded by the pendaftar
    public function documents()
    {
        return $this->hasMany(\App\Models\PendaftarDocuments::class, 'pendaftar_id');
    }

    // Payments relation (if you scaffolded a payments table)
    public function payments()
    {
        return $this->hasMany(\App\Models\PendaftarPembayaran::class, 'pendaftar_id');
    }

    // Accessors
    public function getUmurAttribute()
    {
        if (! $this->tanggal_lahir) {
            return null;
        }

        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Get status dokumen badge class
     */
    public function getStatusDokumenBadgeAttribute()
    {
        return match($this->status_dokumen) {
            'belum_upload' => 'secondary',
            'upload_parsial' => 'warning', 
            'menunggu_review' => 'info',
            'review_revision' => 'danger',
            'dokumen_diterima' => 'success',
            'dokumen_ditolak' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status dokumen display text
     */
    public function getStatusDokumenTextAttribute()
    {
        return match($this->status_dokumen) {
            'belum_upload' => 'Belum Upload',
            'upload_parsial' => 'Upload Parsial', 
            'menunggu_review' => 'Menunggu Review',
            'review_revision' => 'Perlu Revisi',
            'dokumen_diterima' => 'Dokumen Diterima',
            'dokumen_ditolak' => 'Dokumen Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Check if dokumen sudah lengkap dan layak direview
     */
    public function isDokumenReadyForReview()
    {
        // Logic untuk cek apakah semua dokumen wajib sudah diupload
        $dokumenWajib = $this->periodePendaftaran
            ->dokumenPendaftars()
            ->wherePivot('is_wajib', true)
            ->count();
            
        $dokumenWajibUploaded = $this->documents()
            ->whereHas('dokumenPendaftar', function($query) {
                $query->whereIn('id', $this->periodePendaftaran
                    ->dokumenPendaftars()
                    ->wherePivot('is_wajib', true)
                    ->pluck('id')
                );
            })
            ->count();
            
        return $dokumenWajib > 0 && $dokumenWajibUploaded >= $dokumenWajib;
    }

    /**
     * Auto update status dokumen berdasarkan kondisi upload
     */
    public function updateStatusDokumen()
    {
        $totalDokumen = $this->periodePendaftaran->dokumenPendaftars()->count();
        $dokumenUploaded = $this->documents()->count();
        
        if ($dokumenUploaded === 0) {
            $this->status_dokumen = 'belum_upload';
        } elseif ($dokumenUploaded < $totalDokumen || !$this->isDokumenReadyForReview()) {
            $this->status_dokumen = 'upload_parsial';
        } elseif ($this->isDokumenReadyForReview() && $this->status_dokumen === 'upload_parsial') {
            $this->status_dokumen = 'menunggu_review';
        }
        
        $this->save();
    }

    // Boot: generate nomor_pendaftaran if not provided
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->nomor_pendaftaran)) {
                $model->nomor_pendaftaran = self::generateNomorPendaftaran($model->periode_pendaftaran_id ?? null);
            }
        });
    }

    public static function generateNomorPendaftaran($periodeId = null)
    {
        // Format: PERIODEYYYYMMDD-XXXX
        $prefix = 'PEN';
        if ($periodeId) {
            $prefix = 'P' . str_pad($periodeId, 3, '0', STR_PAD_LEFT);
        }

        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        return sprintf('%s-%s-%s', $prefix, $date, $random);
    }

    /**
     * Check if user has already passed registration phase
     */
    public static function hasActiveRegistration($googleUserId)
    {
        return self::where('google_user_id', $googleUserId)
            ->where('status', 'submitted')
            ->whereHas('payments', function($query) {
                $query->where('status', 'confirmed');
            })
            ->exists();
    }

    /**
     * Check if user has ongoing registration (draft, submitted but not confirmed, or rejected payment)
     */
    public static function hasOngoingRegistration($googleUserId)
    {
        return self::where('google_user_id', $googleUserId)
            ->where(function($query) {
                // Draft status
                $query->where('status', 'draft')
                    // Or submitted but waiting for payment
                    ->orWhere(function($subQuery) {
                        $subQuery->where('status', 'submitted')
                            ->whereDoesntHave('payments');
                    })
                    // Or submitted with pending/rejected payment
                    ->orWhere(function($subQuery) {
                        $subQuery->where('status', 'submitted')
                            ->whereHas('payments', function($paymentQuery) {
                                $paymentQuery->whereIn('status', ['pending', 'rejected']);
                            });
                    });
            })
            ->exists();
    }

    /**
     * Check if user can register for new period
     */
    public static function canRegisterNewPeriod($googleUserId)
    {
        // Tidak bisa daftar jika sudah lolos atau ada pendaftaran yang sedang berjalan
        return !self::hasActiveRegistration($googleUserId) && !self::hasOngoingRegistration($googleUserId);
    }

    /**
     * Get ongoing registration for user
     */
    public static function getOngoingRegistration($googleUserId)
    {
        return self::with(['periodePendaftaran', 'payments'])
            ->where('google_user_id', $googleUserId)
            ->where(function($query) {
                // Draft status
                $query->where('status', 'draft')
                    // Or submitted but waiting for payment
                    ->orWhere(function($subQuery) {
                        $subQuery->where('status', 'submitted')
                            ->whereDoesntHave('payments');
                    })
                    // Or submitted with pending/rejected payment
                    ->orWhere(function($subQuery) {
                        $subQuery->where('status', 'submitted')
                            ->whereHas('payments', function($paymentQuery) {
                                $paymentQuery->whereIn('status', ['pending', 'rejected']);
                            });
                    });
            })
            ->latest()
            ->first();
    }

    /**
     * Get active registration for user
     */
    public static function getActiveRegistration($googleUserId)
    {
        return self::with(['periodePendaftaran', 'payments'])
            ->where('google_user_id', $googleUserId)
            ->where('status', 'submitted')
            ->whereHas('payments', function($query) {
                $query->where('status', 'confirmed');
            })
            ->first();
    }
}
