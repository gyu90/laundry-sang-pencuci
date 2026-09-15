<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ServicePackage;
use App\Models\Service;
use App\Models\OrderItem;
use App\Services\LoyaltyService;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;


class OrderController extends Controller
{
    /**
     * Menampilkan daftar order yang masih aktif.
     */
    public function index()
{
   $orders = Order::with([
    'customer',
    'createdByStaff',
    'items.servicePackage',
    'payment',
])
->where('status', '!=', 'Selesai')
->latest('order_date')
->paginate(5);

    $customers = Customer::orderBy('name')->get();

    $servicePackages = ServicePackage::with('service')
        ->where('is_active', true)
        ->orderBy('package_name')
        ->get();

    $services = Service::where('is_active', true)
    ->orderBy('service_name')
    ->get();

$vouchers = Voucher::with([
        'program.freeServicePackage.service'
    ])
    ->where('status', 'available')
    ->where('expiry_at', '>=', now())
    ->get();

    
    return view('staff.orders.index', compact(
    'orders',
    'customers',
    'servicePackages',
    'services',
    'vouchers'
));
}

/**
 * Menampilkan riwayat order yang sudah selesai.
 */
public function history(Request $request)
{
   $query = Order::with([
    'customer',
    'createdByStaff',
    'items.servicePackage',
    'payment',
])
    ->where('status', 'Selesai');

    // Pencarian berdasarkan nomor order
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where('id', 'like', '%' . $search . '%');
    }

    // Filter berdasarkan bulan
    if ($request->filled('month')) {
        $query->whereMonth('order_date', $request->month);
    }

    // Filter berdasarkan tahun
    if ($request->filled('year')) {
        $query->whereYear('order_date', $request->year);
    }

    // Filter berdasarkan tanggal
   if ($request->filled('date_from')) {
    $query->whereDate(
        'order_date',
        '>=',
        $request->date_from
    );
}

if ($request->filled('date_to')) {
    $query->whereDate(
        'order_date',
        '<=',
        $request->date_to
    );
}

// Ambil daftar bulan yang memiliki order selesai
$months = (clone $query)
    ->selectRaw('DATE_FORMAT(order_date, "%Y-%m") as month')
    ->distinct()
    ->orderByDesc('month')
    ->pluck('month');

// Ambil order dan pagination untuk masing-masing bulan
$monthlyOrders = collect();

foreach ($months as $month) {

    $monthDate = \Carbon\Carbon::createFromFormat(
        'Y-m',
        $month
    );

    $monthOrders = (clone $query)
        ->whereYear('order_date', $monthDate->year)
        ->whereMonth('order_date', $monthDate->month)
        ->latest('order_date')
        ->paginate(
            5,
            ['*'],
            'page_' . str_replace('-', '_', $month)
        );

    $monthlyOrders->put(
        $month,
        $monthOrders
    );
}

return view(
    'staff.orders.history',
    compact('monthlyOrders')
);
}


public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',

        'service_package_id' => 'required|array|min:1',

        'service_package_id.*' =>
            'required|exists:service_packages,id',

        'quantity' => 'required|array|min:1',

        'quantity.*' =>
            'required|integer|min:1',
        
        'payment_status' => 'required|in:Belum Lunas,Lunas',

        'applied_voucher_id' =>
            'nullable|exists:vouchers,id',
    ]);

    DB::transaction(function () use ($request) {

        /*
        |--------------------------------------------------------------------------
        | 1. CEK VOUCHER
        |--------------------------------------------------------------------------
        */

        $voucher = null;

        if ($request->filled('applied_voucher_id')) {

           $voucher = Voucher::with([
        'program.freeServicePackage'
    ])
    ->where('id', $request->applied_voucher_id)
    ->where('customer_id', $request->customer_id)
    ->where('status', 'available')
    ->where('expiry_at', '>=', now())
    ->first();

            if (!$voucher) {

                abort(
                    422,
                    'Voucher tidak tersedia, sudah digunakan, atau sudah kedaluwarsa.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 2. BUAT ORDER AWAL
        |--------------------------------------------------------------------------
        */

        $order = Order::create([

            'customer_id' => $request->customer_id,

            'order_date' => now(),

            'total_amount' => 0,

            'discount_amount' => 0,

            'final_amount' => 0,

            'status' => 'Dalam Antrian',

            'applied_voucher_id' =>
                $voucher?->id,

            'created_by_staff_id' =>
                auth()->user()->staff->id,

        ]);

    Payment::create([
    'order_id' => $order->id,
    'status' => $request->payment_status,
    'paid_at' => $request->payment_status === 'Lunas'
        ? now()
        : null,
    'confirmed_by_staff_id' => $request->payment_status === 'Lunas'
        ? auth()->user()->staff->id
        : null,
]);


        /*
        |--------------------------------------------------------------------------
        | 3. SIMPAN ITEM ORDER
        |--------------------------------------------------------------------------
        */

        foreach (
            $request->service_package_id
            as $index => $packageId
        ) {

            $package =
                ServicePackage::findOrFail($packageId);

            $quantity =
                $request->quantity[$index];

            OrderItem::create([

                'order_id' => $order->id,

                'service_package_id' =>
                    $package->id,

                'quantity' =>
                    $quantity,

                'unit_price' =>
                    $package->price,

                'subtotal' =>
                    $package->price * $quantity,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. HITUNG TOTAL ORDER
        |--------------------------------------------------------------------------
        */

        $totalAmount =
            OrderItem::where('order_id', $order->id)
                ->sum('subtotal');


        /*
        |--------------------------------------------------------------------------
        | 5. HITUNG POTONGAN VOUCHER
        |--------------------------------------------------------------------------
        */

        $discountAmount = 0;

if ($voucher) {

    // ==========================================
    // VOUCHER GRATIS LAYANAN
    // ==========================================
    if ($voucher->reward_type === 'free_service') {

        $freePackageId =
            $voucher->program?->free_service_package_id;

        $freeQuantity =
            (int) $voucher->reward_value;

        if ($freePackageId && $freeQuantity > 0) {

            foreach (
                $request->service_package_id
                as $index => $packageId
            ) {

                // Hanya paket yang sama dengan
                // paket yang diberikan gratis
                if ((int) $packageId === (int) $freePackageId) {

                    $package =
                        ServicePackage::findOrFail($packageId);

                    $quantity =
                        (int) $request->quantity[$index];

                    // Jumlah gratis tidak boleh
                    // melebihi jumlah yang dipesan
                    $gratis =
                        min($quantity, $freeQuantity);

                    $discountAmount +=
                        $package->price * $gratis;

                    // Voucher hanya memberikan
                    // sejumlah paket sesuai reward_value
                    $freeQuantity -= $gratis;

                    if ($freeQuantity <= 0) {
                        break;
                    }
                }
            }
        }
    }

    // ==========================================
    // VOUCHER POTONGAN NOMINAL
    // ==========================================
    elseif (
        $voucher->reward_type === 'discount_nominal'
    ) {

        $discountAmount =
            $voucher->reward_value;
    }

    // ==========================================
    // VOUCHER POTONGAN PERSENTASE
    // ==========================================
    elseif (
        $voucher->reward_type === 'discount_percentage'
    ) {

        $discountAmount =
            $totalAmount *
            ($voucher->reward_value / 100);
    }

    // Potongan tidak boleh melebihi total
    $discountAmount = min(
        $discountAmount,
        $totalAmount
    );
}


        /*
        |--------------------------------------------------------------------------
        | 6. HITUNG TOTAL AKHIR
        |--------------------------------------------------------------------------
        */

        $finalAmount =
            $totalAmount - $discountAmount;


        /*
        |--------------------------------------------------------------------------
        | 7. UPDATE ORDER
        |--------------------------------------------------------------------------
        */

        $order->update([

            'total_amount' =>
                $totalAmount,

            'discount_amount' =>
                $discountAmount,

            'final_amount' =>
                $finalAmount,

        ]);


        /*
        |--------------------------------------------------------------------------
        | 8. TANDAI VOUCHER SEBAGAI USED
        |--------------------------------------------------------------------------
        */

        if ($voucher) {

            $voucher->update([

                'status' => 'used',

                'applied_order_id' =>
                    $order->id,

                'applied_at' =>
                    now(),

                'applied_by_staff_id' =>
                    auth()->user()->staff->id,

            ]);
        }

    });


    /*
    |--------------------------------------------------------------------------
    | 9. KEMBALI KE HALAMAN ORDER
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('staff.orders.index')
        ->with(
            'success',
            'Order berhasil dibuat.'
        );
}

public function updateStatus(
    Request $request,
    Order $order,
    LoyaltyService $loyaltyService
) {
    $request->validate([
        'status' => 'required|in:Dalam Antrian,Proses,Siap Dijemput,Siap Diantar,Selesai',
    ]);

    // Jika admin mencoba menyelesaikan order,
    // pastikan pembayaran sudah lunas.
    if (
        $request->status === 'Selesai'
        && $order->payment?->status !== 'Lunas'
    ) {
        return redirect()
            ->route('staff.orders.index')
            ->with(
                'error',
                'Pesanan belum lunas dan belum dapat diselesaikan.'
            );
    }

    // Simpan status sebelumnya
    $oldStatus = $order->status;

    // Update status
    $order->update([
        'status' => $request->status,
    ]);

    /*
     * Loyalty hanya diproses ketika order
     * benar-benar berubah menjadi Selesai.
     */
    if (
        $oldStatus !== 'Selesai' &&
        $request->status === 'Selesai'
    ) {
        $loyaltyService->processCompletedOrder(
            $order->fresh()
        );
    }

    return redirect()
        ->route('staff.orders.index')
        ->with(
            'success',
            'Status order berhasil diperbarui.'
        );
}

public function markAsPaid(Order $order)
{
    $payment = $order->payment;

    if (!$payment) {
        return redirect()
            ->route('staff.orders.index')
            ->with('error', 'Data pembayaran order tidak ditemukan.');
    }

    if ($payment->status === 'Lunas') {
        return redirect()
            ->route('staff.orders.index')
            ->with('error', 'Pembayaran order ini sudah lunas.');
    }

    $payment->update([
        'status' => 'Lunas',
        'paid_at' => now(),
        'confirmed_by_staff_id' => auth()->user()->staff->id,
    ]);

    return redirect()
        ->route('staff.orders.index')
        ->with('success', 'Pembayaran order berhasil dikonfirmasi.');
}


}