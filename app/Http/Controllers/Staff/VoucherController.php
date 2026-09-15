<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Customer;
use App\Models\LoyaltyProgram;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
       $vouchers = Voucher::with([
    'customer',
    'program',
    'appliedByStaff'
])
->latest('issued_at')
->get();

        $customers = Customer::orderBy('name')->get();

        $programs = LoyaltyProgram::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('staff.vouchers.index', compact(
            'vouchers',
            'customers',
            'programs'
        ));
    }
}