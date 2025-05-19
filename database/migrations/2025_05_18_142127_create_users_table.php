<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('whatsapp')->nullable();
            $table->rememberToken(); // untuk keperluan auth
            // timestamps tidak digunakan, sesuai model
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
