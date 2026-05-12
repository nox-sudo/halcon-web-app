<?php

namespace App\Http\Controllers;

use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'ordered'    => Order::withoutTrashed()->where('status', 'ordered')->count(),
            'in_process' => Order::withoutTrashed()->where('status', 'in_process')->count(),
            'in_route'   => Order::withoutTrashed()->where('status', 'in_route')->count(),
            'delivered'  => Order::withoutTrashed()->where('status', 'delivered')->count(),
        ];

        $recentOrders = Order::withoutTrashed()
            ->with('creator')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact('stats', 'recentOrders')); // resources/views/dashboard.blade.php
    }
}
