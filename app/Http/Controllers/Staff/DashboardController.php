<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | OMZET HARI INI
        |--------------------------------------------------------------------------
        */

        $todayIncome = Order::whereDate('order_date', today())
            ->sum('final_amount');


        /*
        |--------------------------------------------------------------------------
        | JUMLAH ORDER BERDASARKAN STATUS
        |--------------------------------------------------------------------------
        */

        $waitingOrders = Order::where('status', 'Dalam Antrian')
            ->count();

        $processingOrders = Order::where('status', 'Proses')
            ->count();

        $readyDeliveryOrders = Order::where('status', 'Siap Diantar')
            ->count();

        $readyPickupOrders = Order::where('status', 'Siap Dijemput')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PESANAN TERBARU
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with([
            'customer',
            'items.servicePackage.service',
        ])
            ->latest('order_date')
            ->take(5)
            ->get();


   
$serviceOrders = DB::table('order_items')
    ->join('service_packages', 'order_items.service_package_id', '=', 'service_packages.id')
    ->join('services', 'service_packages.service_id', '=', 'services.id')
    ->join('orders', 'order_items.order_id', '=', 'orders.id')
    ->select(
        'services.service_name',
        DB::raw('COUNT(DISTINCT orders.id) as total')
    )
    ->groupBy('services.id', 'services.service_name')
    ->orderByDesc('total')
    ->get();

$maxServiceOrders = $serviceOrders->max('total') ?? 1;

return view('staff.dashboard', compact(
    'todayIncome',
    'waitingOrders',
    'processingOrders',
    'readyDeliveryOrders',
    'readyPickupOrders',
    'recentOrders',
    'serviceOrders',
    'maxServiceOrders'
));
    }
}