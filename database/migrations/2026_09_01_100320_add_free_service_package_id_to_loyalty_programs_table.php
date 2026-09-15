<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan paket layanan gratis
     * yang digunakan oleh program loyalty.
     */
    public function up(): void
    {
        Schema::table('loyalty_programs', function (Blueprint $table) {
            $table->foreignId('free_service_package_id')
                ->nullable()
                ->after('applicable_service_id')
                ->constrained('service_packages')
                ->nullOnDelete();
        });
    }

    /**
     * Menghapus kembali kolom jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::table('loyalty_programs', function (Blueprint $table) {
            $table->dropForeign([
                'free_service_package_id'
            ]);

            $table->dropColumn('free_service_package_id');
        });
    }
};