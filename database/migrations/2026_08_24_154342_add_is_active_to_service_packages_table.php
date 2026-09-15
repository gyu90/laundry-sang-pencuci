<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom is_active pada tabel service_packages.
     *
     * Kolom ini digunakan untuk menentukan apakah
     * paket layanan masih tersedia atau tidak.
     */
    public function up(): void
    {
        Schema::table('service_packages', function (Blueprint $table) {

            // Menyimpan status aktif/nonaktif paket.
            // true  = paket tersedia
            // false = paket tidak tersedia
            $table->boolean('is_active')
                ->default(true)
                ->after('price');
        });
    }

    /**
     * Menghapus kolom is_active jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::table('service_packages', function (Blueprint $table) {

            // Menghapus kolom is_active
            // ketika menjalankan rollback migration.
            $table->dropColumn('is_active');
        });
    }
};