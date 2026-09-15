<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            // Customer pemilik voucher
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            // Program loyalitas yang menghasilkan voucher
            $table->foreignId('program_id')
                ->constrained('loyalty_programs')
                ->restrictOnDelete();

            // Kode voucher unik
            $table->string('code', 50)
                ->unique();

            // Jenis reward
            $table->enum('reward_type', [
                'free_service',
                'discount_nominal',
                'discount_percentage'
            ]);

            // Nilai reward
            $table->decimal('reward_value', 15, 2);

            // Status voucher
            $table->enum('status', [
                'available',
                'applied',
                'used',
                'expired'
            ])->default('available');

            // Tanggal voucher diterbitkan
            $table->date('issued_date')
                ->useCurrent();

            // Tanggal voucher berakhir
            $table->date('expiry_date');

            // Order tempat voucher diterapkan
            $table->foreignId('applied_order_id')
                ->nullable();

            // Waktu voucher diterapkan
            $table->timestamp('applied_at')
                ->nullable();

            // Staff yang menerapkan voucher
            $table->foreignId('applied_by_staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};