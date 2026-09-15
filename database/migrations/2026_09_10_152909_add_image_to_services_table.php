<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom image pada tabel services.
     *
     * Kolom image digunakan untuk menyimpan
     * nama/path file gambar layanan yang diupload Staff.
     *
     * Nilai NULL berarti layanan menggunakan
     * gambar default yang telah disediakan sistem.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('image')
                ->nullable()
                ->after('service_name');
        });
    }

    /**
     * Menghapus kolom image jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};