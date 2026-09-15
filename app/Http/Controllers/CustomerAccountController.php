<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerAccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $customer = $user->customer;

        // Pesanan yang masih aktif
$activeOrder = $customer?->orders()
    ->with([
        'items.servicePackage',
        'payment',
    ])
    ->where('status', '!=', 'Selesai')
    ->latest('order_date')
    ->first();

        // 3 pesanan selesai terbaru
        $orderHistory = $customer?->orders()
            ->with('items.servicePackage')
            ->where('status', 'Selesai')
            ->latest('order_date')
            ->take(3)
            ->get();

        return view('customer.account', compact(
            'user',
            'customer',
            'activeOrder',
            'orderHistory'
        ));
    }



public function orderHistory()
{
    $user = auth()->user();
    $customer = $user->customer;

    $orderHistory = $customer
        ? $customer->orders()
            ->with('items.servicePackage')
            ->where('status', 'Selesai')
            ->latest('order_date')
            ->paginate(5)
        : collect();

    return view('customer.order-history', compact(
        'user',
        'customer',
        'orderHistory'
    ));
}


   public function pickupOrder(Request $request, $orderId)
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
    ->with('pickup_success', true);
}


public function deliveryRequest($orderId)
{
    $user = auth()->user();
    $customer = $user->customer;

    $order = $customer?->orders()
        ->where('id', $orderId)
        ->where('status', 'Siap Dijemput')
        ->firstOrFail();

    $order->delivery_requested = true;
    $order->save();

    return redirect()
        ->route('customer.account')
        ->with('delivery_success', true);
}

    public function update(Request $request)
    {
         
        $user = auth()->user();
        $customer = $user->customer;

        $validated = $request->validate([
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone,' . $user->id,
            ],
'address' => [
    'nullable',
    'string',
],

'maps_link' => [
'nullable',
'url',
],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah digunakan akun lain.',
            'address.required' => 'Alamat wajib diisi.',
            'maps_link.url' => 'Link Google Maps tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

         

        $user->phone = $validated['phone'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

if ($customer) {
    $customer->address = $validated['address'] ?? null;
    $customer->maps_link = $validated['maps_link'] ?? null;
    $customer->save();
}
        return redirect()
            ->route('customer.account')
            ->with('success', 'Data akun berhasil diperbarui.');
    }
}