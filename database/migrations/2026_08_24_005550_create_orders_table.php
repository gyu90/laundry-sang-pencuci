<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Customer yang melakukan pesanan
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            // Tanggal dan waktu pesanan
            $table->timestamp('order_date')
                ->useCurrent();

            // Total harga sebelum potongan
            $table->decimal('total_amount', 15, 2);

            // Nilai potongan voucher
            $table->decimal('discount_amount', 15, 2)
                ->default(0);

            // Total akhir setelah potongan
            $table->decimal('final_amount', 15, 2);

            // Status keseluruhan order
            $table->enum('status', [
                'Dalam Antrian',
                'Proses',
                'Siap Dijemput',
                'Selesai'
            ])->default('Dalam Antrian');

            // Voucher yang digunakan
            $table->foreignId('applied_voucher_id')
                ->nullable()
                ->constrained('vouchers')
                ->nullOnDelete();

            // Staff yang membuat order
            $table->foreignId('created_by_staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};