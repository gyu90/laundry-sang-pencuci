<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('pickup_requested')
                ->default(false)
                ->after('delivery_requested');
        });
    }


    public function pickupOrder($orderId)
{
    $user = auth()->user();
    $customer = $user->customer;

    $order = $customer?->orders()
        ->where('id', $orderId)
        ->where('status', 'Siap Dijemput')
        ->firstOrFail();

    $order->pickup_requested = true;
    $order->save();

    return redirect()
        ->route('customer.account')
        ->with('success', 'Pesanan berhasil ditandai untuk diambil sendiri.');
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('pickup_requested');
        });
    }
};