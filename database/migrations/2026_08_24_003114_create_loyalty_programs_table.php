<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_programs', function (Blueprint $table) {
            $table->id();

            // Nama program loyalitas
            $table->string('name', 100);

            // Deskripsi program
            $table->text('description')->nullable();

            // Minimal nilai transaksi untuk memenuhi syarat
            $table->decimal('min_transaction_amount', 15, 2);

            // Durasi periode dalam hari
            $table->integer('period_duration_days');

            // Jenis reward
            $table->enum('reward_type', [
                'free_service',
                'discount_nominal',
                'discount_percentage'
            ]);

            // Nilai reward
            $table->decimal('reward_value', 15, 2);

            // Layanan yang dapat menerima reward, opsional
            $table->foreignId('applicable_service_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();

            // Status program
            $table->boolean('is_active')->default(true);

            // Waktu program dibuat
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_programs');
    }
};