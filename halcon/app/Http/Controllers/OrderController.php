<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // ── Lista general ─────────────────────────────
    public function index(Request $request)
    {
        $query = Order::withoutTrashed()->latest();

        if ($request->filled('factura')) {
            $query->where('invoice_number', 'like', '%' . $request->factura . '%');
        }
        if ($request->filled('cliente')) {
            $query->where('customer_number', 'like', '%' . $request->cliente . '%');
        }
        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);

        return view('orders.index', compact('orders'));
    }

    // ── Formulario creación ───────────────────────
    public function create()
    {
        $this->authorize('create', Order::class);
        return view('orders.form');
    }

    // ── Guardar nuevo pedido ──────────────────────
    public function store(Request $request)
    {
        $this->authorize('create', Order::class);

        $validated = $request->validate([
            'invoice_number'  => 'required|string|unique:orders,invoice_number',
            'customer_number' => 'required|string',
            'customer_name'   => 'required|string|max:255',
            'rfc'             => 'nullable|string|max:13',
            'fiscal_regime'   => 'nullable|string|max:100',
            'fiscal_address'  => 'nullable|string',
            'delivery_address'=> 'required|string',
            'notes'           => 'nullable|string',
        ]);

        $validated['status']     = 'ordered';
        $validated['created_by'] = auth()->id();

        Order::create($validated);

        return redirect()->route('orders.index')
            ->with('success', "Pedido {$validated['invoice_number']} registrado correctamente.");
    }

    // ── Ver pedido ────────────────────────────────
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    // ── Formulario edición ────────────────────────
    public function edit(Order $order)
    {
        $this->authorize('update', $order);
        return view('orders.form', compact('order'));
    }

    // ── Actualizar pedido ─────────────────────────
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'invoice_number'  => 'required|string|unique:orders,invoice_number,' . $order->id,
            'customer_number' => 'required|string',
            'customer_name'   => 'required|string|max:255',
            'rfc'             => 'nullable|string|max:13',
            'fiscal_regime'   => 'nullable|string|max:100',
            'fiscal_address'  => 'nullable|string',
            'delivery_address'=> 'required|string',
            'notes'           => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pedido actualizado correctamente.');
    }

    // ── Cambio de estado ──────────────────────────
    public function changeStatus(Request $request, Order $order)
    {
        $this->authorize('changeStatus', $order);

        $request->validate([
            'status' => 'required|in:in_process,in_route,delivered',
        ]);

        $statusOrder   = Order::STATUS_ORDER;
        $currentIndex  = array_search($order->status, $statusOrder);
        $newIndex      = array_search($request->status, $statusOrder);

        // Solo avanzar al siguiente estado
        if ($newIndex !== $currentIndex + 1) {
            return back()->with('error', 'Cambio de estado no válido.');
        }

        $order->update(['status' => $request->status]);

        $label = Order::STATUSES[$request->status];
        return redirect()->route('orders.show', $order)
            ->with('success', "Estado actualizado a: {$label}");
    }

    // ── Subir foto de evidencia ───────────────────
    public function uploadPhoto(Request $request, Order $order)
    {
        $request->validate([
            'photo'      => 'required|image|max:5120',
            'photo_type' => 'required|in:route,delivery',
        ]);

        $path = $request->file('photo')->store('evidence', 'public');

        if ($request->photo_type === 'route') {
            $order->update(['route_photo' => $path]);
            $msg = 'Foto de carga subida correctamente.';
        } else {
            $order->update(['delivery_photo' => $path]);
            $msg = 'Foto de entrega subida correctamente.';
        }

        return redirect()->route('orders.show', $order)->with('success', $msg);
    }

    // ── Eliminar lógico (archivar) ────────────────
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);
        $order->delete();

        return redirect()->route('orders.index')
            ->with('info', "Pedido {$order->invoice_number} archivado.");
    }

    // ── Pedidos archivados ────────────────────────
    public function archived()
    {
        $orders = Order::onlyTrashed()->latest('deleted_at')->paginate(15);
        return view('orders.archived', compact('orders'));
    }

    // ── Restaurar pedido archivado ────────────────
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $order);
        $order->restore();

        return redirect()->route('orders.archived')
            ->with('success', "Pedido {$order->invoice_number} restaurado.");
    }
}
