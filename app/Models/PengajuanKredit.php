<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_fasilitas',
        'jumlah_pinjaman',
        'jangka_waktu',
        'nama_lengkap',
        'no_ktp',
        'jenis_kelamin',
        'no_hp',
        'alamat_tinggal',
        'alamat_ktp',
        'status_perkawinan',
        'no_npwp',
        'pendidikan_terakhir',
        'agama',
        'nama_ibu_kandung',
        'status_rumah',
        'email',

        // pasangan
        'pasangan_nama',
        'pasangan_no_ktp',
        'pasangan_alamat_tinggal',
        'pasangan_alamat_ktp',
        'pasangan_pekerjaan',
        'pasangan_email',

        // penjamin
        'penjamin_nama',
        'penjamin_no_ktp',
        'penjamin_hubungan',
        'penjamin_alamat',
        'penjamin_email',

        'status',
    ];

    // Relasi ke user (setiap pengajuan dimiliki oleh 1 user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
