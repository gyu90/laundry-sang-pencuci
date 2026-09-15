<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Order induk
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Package layanan yang dipilih
            $table->foreignId('service_package_id')
                ->constrained('service_packages')
                ->restrictOnDelete();

            // Jumlah layanan/package
            $table->integer('quantity')
                ->default(1);

            // Harga package saat transaksi
            $table->decimal('unit_price', 15, 2);

            // quantity × unit_price
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};