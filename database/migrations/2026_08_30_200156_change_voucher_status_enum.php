<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->enum('status', [
                'available',
                'used',
                'expired'
            ])->default('available')->change();
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->enum('status', [
                'available',
                'applied',
                'used',
                'expired'
            ])->default('available')->change();
        });
    }
};