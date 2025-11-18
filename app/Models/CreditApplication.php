<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credit_facility_id',
        'tujuan_pinjaman',
        'jumlah_pinjaman',
        'jangka_waktu',
        'sumber_pendapatan',
        'status',
        'requires_npwp',
        'approved_by',
        'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function nasabahProfile()
    {
        return $this->belongsTo(NasabahProfile::class, 'user_id', 'user_id');
    }

    public function creditFacility()
    {
        return $this->belongsTo(CreditFacility::class);
    }

    public function detail()
    {
        return $this->hasOne(CreditApplicationDetail::class);
    }

    public function collateral()
    {
        return $this->hasOne(CreditCollateral::class);
    }

    public function documents()
    {
        return $this->hasMany(CreditDocument::class);
    }
}
