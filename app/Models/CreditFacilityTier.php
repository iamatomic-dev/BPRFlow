<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditFacilityTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_facility_id',
        'min_plafond',
        'max_plafond',
        'suku_bunga',
    ];

    public function creditFacility()
    {
        return $this->belongsTo(CreditFacility::class);
    }
}
