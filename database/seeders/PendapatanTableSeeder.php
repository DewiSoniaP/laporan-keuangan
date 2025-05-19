<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendapatanTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pendapatan')->insert([
            [
                'tanggal' => '2025-05-01',
                'namaPasien' => 'Budi Santoso',
                'usia' => 45,
                'namaKeluarga' => 'Siti Aminah',
                'alamat' => 'Jl. Kenanga No.12, Bandung',
                'diagnose' => 'Hipertensi',
                'jenisKunjungan' => 'Kunjungan Rumah',
                'jasa' => 150000.00,
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-05-03',
                'namaPasien' => 'Dewi Lestari',
                'usia' => 32,
                'namaKeluarga' => 'Ahmad Zaki',
                'alamat' => 'Jl. Melati No.5, Jakarta',
                'diagnose' => 'Demam Tinggi',
                'jenisKunjungan' => 'Rawat Jalan',
                'jasa' => 120000.00,
                'is_verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
