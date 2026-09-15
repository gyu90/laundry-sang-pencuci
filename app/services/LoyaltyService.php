<?php

namespace App\Services;

use App\Models\Order;
use App\Models\LoyaltyProgram;
use App\Models\CustomerLoyaltyTracking;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoyaltyService
{
    /**
     * Memproses loyalty ketika order selesai.
     */
    public function processCompletedOrder(Order $order): void
    {
        // Order harus memiliki customer
        if (!$order->customer_id) {
            return;
        }

        // Cari program loyalty yang sedang berlaku
        $programs = LoyaltyProgram::where('is_active', true)
            ->whereDate('start_date', '<=', $order->order_date)
            ->whereDate('end_date', '>=', $order->order_date)
            ->get();

        foreach ($programs as $program) {
            $this->processProgram($order, $program);
        }
    }

    /**
     * Memproses satu order terhadap satu program loyalty.
     */
    private function processProgram(
        Order $order,
        LoyaltyProgram $program
    ): void {

        /*
         * Jika customer sudah mendapatkan voucher
         * dari program ini, abaikan.
         */
       $hasActiveVoucher = Voucher::where(
    'customer_id',
    $order->customer_id
)
->where('program_id', $program->id)
->where('status', 'available')
->exists();

        if ($hasActiveVoucher) {
    return;
}

        /*
         * Cari atau buat tracking customer
         * untuk periode program ini.
         */
        $tracking = CustomerLoyaltyTracking::firstOrCreate(
            [
                'customer_id' => $order->customer_id,
                'program_id' => $program->id,
                'period_start_date' => $program->start_date,
            ],
            [
                'period_end_date' => $program->end_date,
                'total_spent_in_period' => 0,
                'is_eligible' => false,
                'last_updated_at' => now(),
            ]
        );

        /*
         * Ambil seluruh order selesai customer
         * yang berada dalam periode program.
         */
        $totalSpent = Order::where('customer_id', $order->customer_id)
            ->where('status', 'Selesai')
            ->whereDate(
                'order_date',
                '>=',
                $program->start_date
            )
            ->whereDate(
                'order_date',
                '<=',
                $program->end_date
            )
            ->sum('final_amount');

        /*
         * Update total transaksi customer
         * selama periode program.
         */
        $tracking->update([
            'total_spent_in_period' => $totalSpent,
            'is_eligible' =>
                $totalSpent >= $program->min_transaction_amount,
            'last_updated_at' => now(),
        ]);

        /*
         * Belum mencapai minimum transaksi.
         */
        if ($totalSpent < $program->min_transaction_amount) {
            return;
        }

        /*
         * Customer sudah eligible.
         * Terbitkan satu voucher untuk program ini.
         */
        $this->issueVoucher($order, $program, $tracking);
    }

    /**
     * Menerbitkan voucher loyalty.
     */
    private function issueVoucher(
        Order $order,
        LoyaltyProgram $program,
        CustomerLoyaltyTracking $tracking
    ): void {

        // Pengaman agar tidak terjadi voucher ganda.
        $existingActiveVoucher = Voucher::where(
    'customer_id',
    $order->customer_id
)
->where('program_id', $program->id)
->where('status', 'available')
->exists();

if ($existingActiveVoucher) {
    return;
}

        /*
         * Voucher berlaku sampai akhir periode program.
         *
         * Contoh:
         * Program 1–31 Agustus
         * expiry_at = 31 Agustus 23:59:59
         */
        $issuedAt = now();

        $expiryAt = $program->end_date
            ->copy()
            ->endOfDay();

        Voucher::create([
            'customer_id' => $order->customer_id,
            'program_id' => $program->id,
            'code' => $this->generateVoucherCode(),
            'reward_type' => $program->reward_type,
            'reward_value' => $program->reward_value,
            'status' => 'available',
            'issued_at' => $issuedAt,
            'expiry_at' => $expiryAt,
        ]);
    }

    /**
     * Membuat kode voucher unik.
     */
    private function generateVoucherCode(): string
    {
        do {
            $code = 'LYL-' . strtoupper(Str::random(8));
        } while (
            Voucher::where('code', $code)->exists()
        );

        return $code;
    }
}