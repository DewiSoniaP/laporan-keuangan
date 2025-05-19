<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendapatan', function (Blueprint $table) {
            $table->id('idPendapatan');
            $table->date('tanggal');
            $table->string('namaPasien');
            $table->integer('usia');
            $table->string('namaKeluarga');
            $table->string('alamat');
            $table->string('diagnose');
            $table->string('jenisKunjungan');
            $table->decimal('jasa', 15, 2);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendapatan');
    }
};
