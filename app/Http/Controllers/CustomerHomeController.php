<?php

namespace App\Http\Controllers;

use App\Models\Service;

class CustomerHomeController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)
            ->with([
                'servicePackages' => function ($query) {
                    $query->where('is_active', true);
                }
            ])
            ->get();

        return view('customer.home', compact('services'));
    }
}