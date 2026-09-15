<?php

namespace App\Http\Controllers\Owner;

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

        $omzetHariIni = Order::whereDate('order_date', today())
            ->where('status', '!=', 'Selesai')
            ->sum('final_amount');


        /*
        |--------------------------------------------------------------------------
        | JUMLAH PESANAN BERDASARKAN STATUS
        |--------------------------------------------------------------------------
        */

        $antrian = Order::where('status', 'Dalam Antrian')
            ->count();

        $diproses = Order::where('status', 'Proses')
            ->count();

        $siapDiantar = Order::where('status', 'Siap Diantar')
            ->count();

        $siapDijemput = Order::where('status', 'Siap Dijemput')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PESANAN TERBARU
        |--------------------------------------------------------------------------
        */

        $pesananTerbaru = Order::with([
            'customer',
            'items.servicePackage.service'
        ])
        ->latest('order_date')
        ->take(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | PESANAN PER LAYANAN
        |--------------------------------------------------------------------------
        */

        $pesananPerKategori = DB::table('order_items')
            ->join(
                'service_packages',
                'order_items.service_package_id',
                '=',
                'service_packages.id'
            )
            ->join(
                'services',
                'service_packages.service_id',
                '=',
                'services.id'
            )
            ->join(
                'orders',
                'order_items.order_id',
                '=',
                'orders.id'
            )
            ->select(
                'services.service_name',
                DB::raw('COUNT(DISTINCT orders.id) as total')
            )
            ->groupBy('services.id', 'services.service_name')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK PROGRESS BAR
        |--------------------------------------------------------------------------
        */

        $jumlahMaksimalKategori = $pesananPerKategori->max('total') ?? 1;


        return view('owner.dashboard', compact(
            'omzetHariIni',
            'antrian',
            'diproses',
            'siapDiantar',
            'siapDijemput',
            'pesananTerbaru',
            'pesananPerKategori',
            'jumlahMaksimalKategori'
        ));
    }
}