<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id('idPengeluaran'); // sesuai dengan model Anda
            $table->date('tanggal');
            $table->string('keperluanPengeluaran');
            $table->decimal('jumlahPengeluaran', 15, 2);
            $table->string('keterangan')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps(); // langsung tambahkan timestamps di sini
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};

