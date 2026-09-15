<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Nomor HP sebagai identitas utama akun dan login
            $table->string('phone', 20)->unique();

            // Password disimpan dalam bentuk hash
            $table->string('password');

            // Jenis pengguna
            $table->enum('user_type', [
                'staff',
                'customer',
            ]);

            // Status akun: aktif atau tidak aktif
            $table->boolean('is_active')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};