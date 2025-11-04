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
        'nama_lengkap',
        'nik',
        'email',
        'no_hp',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'pendidikan_terakhir',
        'status',
        'source',
        'meta',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
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
