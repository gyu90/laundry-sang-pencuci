<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyProgram;
use App\Models\Voucher;

class CustomerPromoController extends Controller
{
    public function index()
    {
        // Program promo yang masih aktif
        $programs = LoyaltyProgram::where('is_active', true)
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->latest('start_date')
            ->get();

        // Voucher milik customer yang sedang login
        $vouchers = collect();

        if (auth()->check() && auth()->user()->customer) {
            $vouchers = Voucher::with('program')
                ->where('customer_id', auth()->user()->customer->id)
                ->where('status', 'available')
                ->where(function ($query) {
                    $query->whereNull('expiry_at')
                        ->orWhere('expiry_at', '>=', now());
                })
                ->latest('issued_at')
                ->get();
        }

        return view('customer.promo', compact(
            'programs',
            'vouchers'
        ));
    }
}