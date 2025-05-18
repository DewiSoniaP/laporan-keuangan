<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKaryawan extends Model
{
    use HasFactory;
    protected $table = 'datakaryawan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_karyawan',
        'nik',
        'jabatan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'nomor_telepon',
        'gaji',
        'tanggal_masuk',
        'foto',
    ];
}