<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_loyalty_tracking', function (Blueprint $table) {
            $table->id();

            // Customer yang sedang dilacak
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            // Program loyalitas yang diikuti
            $table->foreignId('program_id')
                ->constrained('loyalty_programs')
                ->restrictOnDelete();

            // Awal periode loyalitas
            $table->date('period_start_date');

            // Akhir periode loyalitas
            $table->date('period_end_date');

            // Total transaksi customer selama periode
            $table->decimal('total_spent_in_period', 15, 2)
                ->default(0);

            // Apakah customer memenuhi syarat reward
            $table->boolean('is_eligible')
                ->default(false);

            // Waktu terakhir data tracking diperbarui
            $table->timestamp('last_updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

            // Satu customer tidak boleh memiliki
            // tracking program yang sama pada awal periode yang sama
            $table->unique(
                ['customer_id', 'program_id', 'period_start_date'],
                'unique_tracking'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_loyalty_tracking');
    }
};