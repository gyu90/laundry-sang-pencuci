<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyProgram;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServicePackage;




class LoyaltyProgramController extends Controller
{
    /**
     * Menampilkan daftar program loyalitas.
     */
    public function index()
    {
       $programs = LoyaltyProgram::with('applicableService')
    ->whereDate('end_date', '>=', today())
    ->latest('start_date')
    ->get();

        $services = Service::where('is_active', true)
            ->orderBy('service_name')
            ->get();
        
        $servicePackages = ServicePackage::where('is_active', true)
        ->with('service')
        ->orderBy('package_name')
        ->get();

        return view('staff.loyalty.index', compact(
            'programs',
            'services',
            'servicePackages'
        ));
    }

public function archive()
{
    $programs = LoyaltyProgram::with('applicableService')
        ->whereDate('end_date', '<', today())
        ->latest('end_date')
        ->get();

    return view('staff.loyalty.archive', compact('programs'));
}


public function show(LoyaltyProgram $loyaltyProgram)
{
    $loyaltyProgram->load([
        'customerLoyaltyTrackings.customer',
        'vouchers.customer',
        'vouchers.appliedByStaff',
    ]);

    return view(
        'staff.loyalty.show',
        compact('loyaltyProgram')
    );
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:100',

        'description' => 'nullable|string',

        'min_transaction_amount' =>
            'required|numeric|min:0',

        'reward_type' =>
            'required|in:free_service,discount_nominal,discount_percentage',

        'reward_value' =>
            'required|numeric|min:0',

        'applicable_service_id' =>
            'nullable|exists:services,id',

        'free_service_package_id' =>
         'nullable|exists:service_packages,id',

        'period_start_date' =>
            'required|date',

        'period_end_date' =>
            'required|date|after_or_equal:period_start_date',
    ]);

    LoyaltyProgram::create([
        'name' => $request->name,

        'description' => $request->description,

        'min_transaction_amount' =>
            $request->min_transaction_amount,

        'start_date' =>
            $request->period_start_date,

        'end_date' =>
            $request->period_end_date,

        'reward_type' =>
            $request->reward_type,

        'reward_value' =>
            $request->reward_value,

        'applicable_service_id' =>
            $request->applicable_service_id ?: null,
        
        'free_service_package_id' =>
        $request->free_service_package_id ?: null,

        'is_active' => true,
    ]);

    return redirect()
        ->route('staff.loyalty.index')
        ->with('success', 'Program loyalitas berhasil dibuat.');

    }


public function update(Request $request, LoyaltyProgram $loyaltyProgram)
{
    $request->validate([
        'name' => 'required|string|max:100',

        'description' => 'nullable|string',

        'min_transaction_amount' =>
            'required|numeric|min:0',

        'reward_type' =>
            'required|in:free_service,discount_nominal,discount_percentage',

        'reward_value' =>
            'required|numeric|min:1',

        'applicable_service_id' =>
            'nullable|exists:services,id',

        'free_service_package_id' =>
            'nullable|exists:service_packages,id',

        'period_start_date' =>
            'required|date',

        'period_end_date' =>
            'required|date|after_or_equal:period_start_date',
    ]);

    $loyaltyProgram->update([
        'name' =>
            $request->name,

        'description' =>
            $request->description,

        'min_transaction_amount' =>
            $request->min_transaction_amount,

        'start_date' =>
            $request->period_start_date,

        'end_date' =>
            $request->period_end_date,

        'reward_type' =>
            $request->reward_type,

        'reward_value' =>
            $request->reward_value,

        'applicable_service_id' =>
            $request->applicable_service_id ?: null,

        'free_service_package_id' =>
            $request->free_service_package_id ?: null,
    ]);

    return redirect()
        ->route('staff.loyalty.index')
        ->with(
            'success',
            'Program loyalitas berhasil diperbarui.'
        );
}


}