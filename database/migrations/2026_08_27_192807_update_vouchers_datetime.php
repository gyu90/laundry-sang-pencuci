<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'issued_date',
                'expiry_date',
            ]);

            $table->timestamp('issued_at')->after('status');
            $table->timestamp('expiry_at')->after('issued_at');
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn([
                'issued_at',
                'expiry_at',
            ]);

            $table->date('issued_date')->after('status');
            $table->date('expiry_date')->after('issued_date');
        });
    }
};