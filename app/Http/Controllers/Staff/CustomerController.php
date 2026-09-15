<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Staff;



class CustomerController extends Controller
{
    /**
     * Menampilkan daftar customer.
     */

    
    public function index()
    {

    
     $customers = Customer::with([
        'user',
        'createdByStaff'
    ])
        ->latest()
        ->paginate(5);

    return view('staff.customers.index', compact('customers'));
    }

public function store(Request $request)
{
   
    $validated = $request->validate([
    'name' => ['required', 'string', 'max:100'],
    'phone' => [
        'required',
        'string',
        'max:20',
        'unique:users,phone',
    ],
    'email' => [
        'nullable',
        'email',
        'max:100',
    ],
    'address' => [
    'nullable',
    'string',
],

'maps_link' => [
    'nullable',
    'url',
],

]);
    

    DB::transaction(function () use ($validated) {

        // ======================================================
        // 1. BUAT AKUN USER CUSTOMER
        // ======================================================

       $user = User::create([
    'phone' => $validated['phone'],
    'password' => Hash::make('pelanggan12345'),
    'user_type' => 'customer',
    'is_active' => true,
]);



        // ======================================================
        // 2. BUAT DATA CUSTOMER
        // ======================================================

Customer::create([
    'user_id' => $user->id,
    'created_by_staff_id' => auth()->user()->staff->id,
    'name' => $validated['name'],
    'email' => $validated['email'] ?? null,
    'address' => $validated['address'] ?? null,
    'maps_link' => $validated['maps_link'] ?? null,
]);



    });

    return redirect()
        ->route('staff.customers.index')
        ->with('success', 'Customer berhasil ditambahkan.');
}

public function edit(Customer $customer)
{
    return response()->json([
        'id' => $customer->id,
        'name' => $customer->name,
        'phone' => $customer->user?->phone,
        'email' => $customer->email,
        'address' => $customer->address,
        'maps_link' => $customer->maps_link,
    ]);
}

public function update(Request $request, Customer $customer)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'phone' => [
            'required',
            'string',
            'max:20',
            Rule::unique('users', 'phone')->ignore($customer->user_id),
        ],
        'email' => [
            'nullable',
            'email',
            'max:100',
        ],
        'address' => [
            'nullable',
            'string',
        ],

        'maps_link' => [
    'nullable',
    'url',
],
    ]);

    

    DB::transaction(function () use ($validated, $customer) {

$customer->update([
    'name' => $validated['name'],
    'email' => $validated['email'] ?? null,
    'address' => $validated['address'] ?? null,
    'maps_link' => $validated['maps_link'] ?? null,
]);

        $customer->user->update([
            'phone' => $validated['phone'],
        ]);
    });

    return redirect()
        ->route('staff.customers.index')
        ->with('success', 'Data customer berhasil diperbarui.');
}

public function createdByStaff()
{
    return $this->belongsTo(Staff::class, 'created_by_staff_id');
}

}

