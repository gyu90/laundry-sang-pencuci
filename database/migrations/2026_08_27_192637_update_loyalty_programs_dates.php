<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loyalty_programs', function (Blueprint $table) {
            $table->dropColumn('period_duration_days');

            $table->date('start_date')->after('description');
            $table->date('end_date')->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('loyalty_programs', function (Blueprint $table) {
            $table->dropColumn([
                'start_date',
                'end_date',
            ]);

            $table->integer('period_duration_days')
                ->after('min_transaction_amount');
        });
    }
};