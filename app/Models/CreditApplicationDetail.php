<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditApplicationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_application_id',
        'nama_pasangan',
        'no_ktp_pasangan',
        'alamat_tinggal_pasangan',
        'alamat_ktp_pasangan',
        'pekerjaan_pasangan',
        'email_pasangan',
        'no_hp_pasangan',
        'nama_penjamin',
        'no_ktp_penjamin',
        'hubungan_penjamin',
        'alamat_penjamin',
        'email_penjamin',
        'no_hp_penjamin'
    ];

    public function creditApplication()
    {
        return $this->belongsTo(CreditApplication::class);
    }
}
