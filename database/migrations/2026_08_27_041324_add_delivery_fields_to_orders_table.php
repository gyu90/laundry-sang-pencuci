<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Customer meminta order diantar
            $table->boolean('delivery_requested')
                ->default(false)
                ->after('status');

            // Staff menyetujui permintaan antar
            $table->boolean('delivery_approved')
                ->default(false)
                ->after('delivery_requested');

            // Jadwal antar yang dipilih customer
            $table->time('delivery_time')
                ->nullable()
                ->after('delivery_approved');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_requested',
                'delivery_approved',
                'delivery_time',
            ]);
        });
    }
};