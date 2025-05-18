<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;

    protected $table = 'pendapatan';
    protected $primaryKey = 'idPendapatan';
    public $incrementing = true;              
    protected $keyType = 'int';                

    protected $fillable = [
        'tanggal',
        'namaPasien',
        'usia',
        'namaKeluarga',
        'alamat',
        'diagnose',
        'jenisKunjungan',
        'jasa',
        'is_verified', // TAMBAHKAN agar bisa diisi lewat create/update
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_verified' => 'boolean', // PENTING agar bisa diproses sebagai true/false
    ];
}
