<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengeluaranTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengeluaran')->insert([
            [
                'tanggal' => '2025-05-01',
                'keperluanPengeluaran' => 'Pembelian ATK',
                'jumlahPengeluaran' => 500000.00,
                'keterangan' => 'Pulpen dan kertas',
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tanggal' => '2025-05-05',
                'keperluanPengeluaran' => 'Biaya Listrik',
                'jumlahPengeluaran' => 750000.00,
                'keterangan' => null,
                'is_verified' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
