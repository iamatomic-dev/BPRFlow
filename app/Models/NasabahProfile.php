<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NasabahProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'no_ktp',
        'no_hp',
        'email',
        'alamat_tinggal',
        'alamat_ktp',
        'pendidikan_terakhir',
        'agama',
        'nama_ibu_kandung',
        'status_perkawinan',
        'no_npwp',
        'status_rumah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creditApplications()
    {
        return $this->hasMany(CreditApplication::class, 'user_id', 'user_id');
    }
}
