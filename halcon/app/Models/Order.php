<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_number',
        'customer_name',
        'rfc',
        'fiscal_regime',
        'fiscal_address',
        'delivery_address',
        'notes',
        'status',
        'route_photo',
        'delivery_photo',
        'created_by',
    ];

    const STATUSES = [
        'ordered'    => 'Pedido Registrado',
        'in_process' => 'En Proceso',
        'in_route'   => 'En Ruta',
        'delivered'  => 'Entregado',
    ];

    const STATUS_ORDER = ['ordered', 'in_process', 'in_route', 'delivered'];

    // ── Relationships ─────────────────────────────
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ───────────────────────────────────
    public function statusReached(string $status): bool
    {
        $current = array_search($this->status, self::STATUS_ORDER);
        $target  = array_search($status, self::STATUS_ORDER);
        return $current !== false && $target !== false && $current >= $target;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // ── Scopes ────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('invoice_number', 'like', "%{$term}%")
              ->orWhere('customer_number', 'like', "%{$term}%")
              ->orWhere('customer_name', 'like', "%{$term}%");
        });
    }
}
