<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Menghubungkan customer dengan akun users
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            // Data pelanggan
            $table->string('name', 100);

            // Email bersifat opsional
            $table->string('email', 100)->nullable();

            // Alamat pelanggan
            $table->text('address');

            // Waktu pelanggan didaftarkan
            $table->timestamp('registered_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};