<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Página pública de rastreo de pedidos
    public function index(Request $request)
    {
        $order = null;

        if ($request->filled('factura') && $request->filled('cliente')) {
            $order = Order::withoutTrashed()
                ->where('invoice_number',  $request->factura)
                ->where('customer_number', $request->cliente)
                ->first();
        }

        return view('public.home', compact('order'));
    }
}
