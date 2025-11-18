<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'maksimal_jangka_waktu',
        'deskripsi',
    ];

    /**
     * Relasi ke tier (plafond dan bunga)
     */
    public function tiers()
    {
        return $this->hasMany(CreditFacilityTier::class);
    }

    /**
     * Relasi ke pengajuan kredit
     */
    public function creditApplications()
    {
        return $this->hasMany(CreditApplication::class);
    }
}
