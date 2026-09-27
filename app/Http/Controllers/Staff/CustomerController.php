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

    
public function index(Request $request)
{
    $customers = Customer::with([
        'user',
        'createdByStaff'
    ])
        ->when($request->filled('search'), function ($query) use ($request) {

            $search = trim($request->search);

            $query->where(function ($query) use ($search) {

                // Cari berdasarkan nama customer
                $query->where('name', 'like', '%' . $search . '%')

                    // ATAU cari berdasarkan nomor HP
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('phone', 'like', '%' . $search . '%');
                    });

            });

        })
        ->latest()
        ->paginate(5)
        ->withQueryString();

    return view('staff.customers.index', compact('customers'));
}


public function show(Request $request, Customer $customer)
{
    $orders = $customer->orders()
        ->with([
            'items.servicePackage',
            'createdByStaff'
        ])

        // PENCARIAN NOMOR ORDER
        ->when($request->filled('order_number'), function ($query) use ($request) {

            $orderNumber = strtoupper(trim($request->order_number));

            // Hilangkan prefix ORD-
            $orderId = str_replace('ORD-', '', $orderNumber);

            // Pastikan yang dicari berupa angka
            if (is_numeric($orderId)) {
                $query->where('id', (int) $orderId);
            }

        })

        // FILTER TANGGAL MULAI
        ->when($request->filled('start_date'), function ($query) use ($request) {
            $query->whereDate('order_date', '>=', $request->start_date);
        })

        // FILTER TANGGAL SAMPAI
        ->when($request->filled('end_date'), function ($query) use ($request) {
            $query->whereDate('order_date', '<=', $request->end_date);
        })

        ->latest('order_date')
        ->paginate(5)
        ->withQueryString();

    return view('staff.customers.show', compact(
        'customer',
        'orders'
    ));
}

public function store(Request $request)
{
    $validated = $request->validate([

        'name' => [
            'required',
            'string',
            'max:100',
        ],

        'phone' => [
            'required',
            'string',
            'digits_between:10,15',
            'regex:/^[0-9]+$/',
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

    ], [

        'name.required' => 'Nama customer wajib diisi.',

        'phone.required' => 'Nomor HP wajib diisi.',
        'phone.regex' => 'Nomor HP hanya boleh berisi angka.',
        'phone.digits_between' => 'Nomor HP harus terdiri dari 10 sampai 15 digit.',
        'phone.unique' => 'Nomor HP sudah digunakan.',

        'email.email' => 'Format email tidak valid.',

        'maps_link.url' => 'Format link Google Maps tidak valid.',

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
    'digits_between:10,15',
    'regex:/^[0-9]+$/',
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

