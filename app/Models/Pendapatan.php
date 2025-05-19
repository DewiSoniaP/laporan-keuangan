<?php

// app/Models/Pendapatan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;

    protected $table = 'pendapatan'; // nama tabel di DB

    protected $primaryKey = 'idPendapatan'; // custom primary key

    protected $fillable = [
        'tanggal',
        'namaPasien',
        'usia',
        'namaKeluarga',
        'alamat',
        'diagnose',
        'jenisKunjungan',
        'jasa',
        'is_verified'
    ];
}

