<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false); // Menambahkan kolom is_verified
        });
    }
    
    public function down()
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};
