<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan status "Siap Diantar"
     * pada tabel orders.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'Dalam Antrian',
                'Proses',
                'Siap Dijemput',
                'Siap Diantar',
                'Selesai'
            ])->default('Dalam Antrian')->change();
        });
    }

    /**
     * Mengembalikan status orders
     * ke struktur sebelumnya.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'Dalam Antrian',
                'Proses',
                'Siap Dijemput',
                'Selesai'
            ])->default('Dalam Antrian')->change();
        });
    }
};