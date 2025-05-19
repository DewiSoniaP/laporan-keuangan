<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataKaryawan;

class DataKaryawanTableSeeder extends Seeder
{
    public function run(): void
    {
        DataKaryawan::create([
            'nama_karyawan' => 'Budi Santoso',
            'nik' => '1234567890',
            'jabatan' => 'Manager',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-05-10',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'email' => 'budi@example.com',
            'nomor_telepon' => '081298765432',
            'gaji' => 10000000.00,
            'tanggal_masuk' => '2010-01-15',
            'foto' => null,
        ]);

        DataKaryawan::create([
            'nama_karyawan' => 'Siti Aminah',
            'nik' => '0987654321',
            'jabatan' => 'Staff',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-08-20',
            'alamat' => 'Jl. Sudirman No. 23, Bandung',
            'email' => 'siti@example.com',
            'nomor_telepon' => '081234567890',
            'gaji' => 7000000.00,
            'tanggal_masuk' => '2015-06-01',
            'foto' => null,
        ]);
    }
}
