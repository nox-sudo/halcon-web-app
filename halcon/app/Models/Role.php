<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    const ROLES = [
        ['name' => 'Administrador', 'slug' => 'admin'],
        ['name' => 'Ventas',        'slug' => 'sales'],
        ['name' => 'Compras',       'slug' => 'purchasing'],
        ['name' => 'Almacén',       'slug' => 'warehouse'],
        ['name' => 'Ruta',          'slug' => 'route'],
    ];

    // ── Relationships ─────────────────────────────
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
