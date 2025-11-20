<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'prodi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama_prodi',
        'kode_prodi',
        'deskripsi',
    ];

    // Relationships
    public function pendaftar()
    {
        return $this->hasMany(\App\Models\Pendaftar::class);
    }
}
