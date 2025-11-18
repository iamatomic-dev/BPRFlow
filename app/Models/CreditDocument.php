<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_application_id',
        'nama_file',
        'path',
        'jenis_dokumen',
        'status_verifikasi',
        'verified_at'
    ];

    public function creditApplication()
    {
        return $this->belongsTo(CreditApplication::class);
    }
}
