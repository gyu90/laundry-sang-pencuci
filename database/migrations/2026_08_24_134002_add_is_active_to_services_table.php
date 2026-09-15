<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom is_active pada tabel services.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {

            // Menentukan apakah layanan masih tersedia untuk digunakan.
            // Nilai default true berarti layanan baru otomatis aktif.
            $table->boolean('is_active')
                ->default(true)
                ->after('service_name');
        });
    }

    /**
     * Menghapus kembali kolom is_active
     * jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {

            $table->dropColumn('is_active');
        });
    }
};