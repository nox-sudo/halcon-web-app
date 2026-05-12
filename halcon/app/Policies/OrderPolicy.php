<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // Todo empleado autenticado puede ver pedidos
    public function view(User $user, Order $order): bool
    {
        return $user->is_active;
    }

    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    // Solo Ventas y Admin pueden crear pedidos
    public function create(User $user): bool
    {
        return $user->is_active && in_array($user->role?->slug, ['admin', 'sales']);
    }

    // Admin y Ventas pueden editar datos del pedido
    public function update(User $user, Order $order): bool
    {
        return $user->is_active && in_array($user->role?->slug, ['admin', 'sales']);
    }

    // Solo Admin puede archivar
    public function delete(User $user, Order $order): bool
    {
        return $user->is_active && $user->role?->slug === 'admin';
    }

    // Admin puede restaurar
    public function restore(User $user, Order $order): bool
    {
        return $user->is_active && $user->role?->slug === 'admin';
    }

    // Warehouse y Admin avanzan el estado
    // El ciclo: ordered->in_process (Almacén), in_process->in_route (Almacén), in_route->delivered (Ruta)
    public function changeStatus(User $user, Order $order): bool
    {
        if (!$user->is_active) return false;

        $role = $user->role?->slug;

        if ($role === 'admin') return true;

        return match ($order->status) {
            'ordered'    => $role === 'warehouse',
            'in_process' => $role === 'warehouse',
            'in_route'   => $role === 'route',
            default      => false,
        };
    }

    // Solo Ruta puede subir fotos
    public function uploadPhoto(User $user, Order $order): bool
    {
        return $user->is_active && in_array($user->role?->slug, ['admin', 'route']);
    }
}
