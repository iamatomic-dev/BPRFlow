<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_application_id',
        'angsuran_ke',
        'jatuh_tempo',
        'tagihan_pokok',
        'tagihan_bunga',
        'jumlah_angsuran',
        'denda',
        'jumlah_bayar',
        'tanggal_bayar',
        'bukti_bayar',
        'status_pembayaran',
        'catatan_teller',
    ];
    
    protected $guarded = ['id'];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_bayar' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(CreditApplication::class, 'credit_application_id');
    }
}
