<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $primaryKey = 'idPengeluaran';
    public $incrementing = true;              
    protected $keyType = 'int';

    // Kolom yang dapat diisi melalui mass-assignment
    protected $fillable = [
        'tanggal',
        'keperluanPengeluaran',
        'jumlahPengeluaran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_verified' => 'boolean', // PENTING agar bisa diproses sebagai true/false
    ];
}