<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('datakaryawan', function (Blueprint $table) {
            $table->id(); // primary key
            $table->string('nama_karyawan');
            $table->string('nik')->unique();
            $table->string('jabatan');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('email')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->decimal('gaji', 15, 2);
            $table->date('tanggal_masuk');
            $table->string('foto')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datakaryawan');
    }
};
