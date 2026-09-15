<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();

            // Kelompok layanan
            $table->foreignId('service_id')
                ->constrained('services')
                ->restrictOnDelete();

            // Nama paket/jenis layanan
            $table->string('package_name', 100);

            // Harga layanan
            $table->decimal('price', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};