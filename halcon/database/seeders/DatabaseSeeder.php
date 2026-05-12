<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Roles ──────────────────────────────
        foreach (Role::ROLES as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], ['name' => $role['name']]);
        }

        $adminRole     = Role::where('slug', 'admin')->first();
        $salesRole     = Role::where('slug', 'sales')->first();
        $purchaseRole  = Role::where('slug', 'purchasing')->first();
        $warehouseRole = Role::where('slug', 'warehouse')->first();
        $routeRole     = Role::where('slug', 'route')->first();

        // ── 2. Default Admin User ─────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@halcon.mx'],
            [
                'name'      => 'Administrador Halcón',
                'password'  => Hash::make('halcon2024'),
                'role_id'   => $adminRole->id,
                'is_active' => true,
            ]
        );

        // ── 3. Sample Employees ───────────────────
        $employees = [
            ['name' => 'Carlos Mendoza',   'email' => 'ventas@halcon.mx',   'role' => $salesRole,     'pass' => 'ventas123'],
            ['name' => 'Ana Rodríguez',    'email' => 'compras@halcon.mx',  'role' => $purchaseRole,  'pass' => 'compras123'],
            ['name' => 'Luis Torres',      'email' => 'almacen@halcon.mx',  'role' => $warehouseRole, 'pass' => 'almacen123'],
            ['name' => 'Pedro Castillo',   'email' => 'ruta@halcon.mx',     'role' => $routeRole,     'pass' => 'ruta12345'],
        ];

        foreach ($employees as $emp) {
            User::firstOrCreate(
                ['email' => $emp['email']],
                [
                    'name'      => $emp['name'],
                    'password'  => Hash::make($emp['pass']),
                    'role_id'   => $emp['role']->id,
                    'is_active' => true,
                ]
            );
        }

        // ── 4. Sample Orders ──────────────────────
        $sampleOrders = [
            [
                'invoice_number'  => 'FAC-2024-001',
                'customer_number' => 'CLI-0001',
                'customer_name'   => 'Constructora Novatek S.A. de C.V.',
                'rfc'             => 'CNO980301ABC',
                'fiscal_regime'   => 'Persona Moral',
                'fiscal_address'  => 'Blvd. Hidalgo 142, Col. Centro, Culiacán, Sin.',
                'delivery_address'=> 'Av. Insurgentes 450, Col. Industrial, Culiacán',
                'notes'           => 'Entregar en horario matutino. Preguntar por el Ing. Ramírez.',
                'status'          => 'delivered',
            ],
            [
                'invoice_number'  => 'FAC-2024-002',
                'customer_number' => 'CLI-0002',
                'customer_name'   => 'Obras y Proyectos del Norte',
                'rfc'             => 'OPN001215XYZ',
                'fiscal_regime'   => 'Persona Moral',
                'fiscal_address'  => 'Calle Sinaloa 88, Mazatlán, Sin.',
                'delivery_address'=> 'Fracc. Los Pinos, Manzana 3, Culiacán',
                'notes'           => '',
                'status'          => 'in_route',
            ],
            [
                'invoice_number'  => 'FAC-2024-003',
                'customer_number' => 'CLI-0003',
                'customer_name'   => 'Miguel Ángel Soto Leyva',
                'rfc'             => 'SOLM850520H11',
                'fiscal_regime'   => 'Persona Física',
                'fiscal_address'  => 'Col. Chapultepec, Culiacán',
                'delivery_address'=> 'Calle Pino 220, Col. Rosales, Culiacán',
                'notes'           => 'Llamar al 667-XXX-XXXX antes de llegar.',
                'status'          => 'in_process',
            ],
            [
                'invoice_number'  => 'FAC-2024-004',
                'customer_number' => 'CLI-0004',
                'customer_name'   => 'Desarrolladora Pacifico Sur',
                'rfc'             => 'DPS150601EFG',
                'fiscal_regime'   => 'Persona Moral',
                'fiscal_address'  => 'Av. Obregón 312, Culiacán',
                'delivery_address'=> 'Lote 5, Fracc. Nuevo Culiacán',
                'notes'           => 'Solicita 3 copias de remisión.',
                'status'          => 'ordered',
            ],
            [
                'invoice_number'  => 'FAC-2024-005',
                'customer_number' => 'CLI-0001',
                'customer_name'   => 'Constructora Novatek S.A. de C.V.',
                'rfc'             => 'CNO980301ABC',
                'fiscal_regime'   => 'Persona Moral',
                'fiscal_address'  => 'Blvd. Hidalgo 142, Col. Centro, Culiacán',
                'delivery_address'=> 'Av. Insurgentes 450, Col. Industrial, Culiacán',
                'notes'           => 'Segundo pedido del mes.',
                'status'          => 'ordered',
                'deleted_at'      => now(), // Archived example
            ],
        ];

        foreach ($sampleOrders as $data) {
            $deletedAt = $data['deleted_at'] ?? null;
            unset($data['deleted_at']);

            Order::firstOrCreate(
                ['invoice_number' => $data['invoice_number']],
                array_merge($data, [
                    'created_by' => $admin->id,
                    'deleted_at' => $deletedAt,
                ])
            );
        }

        $this->command->info('✓ Roles, usuarios y pedidos de prueba creados.');
        $this->command->table(
            ['Correo', 'Contraseña', 'Rol'],
            [
                ['admin@halcon.mx',   'halcon2024', 'Administrador'],
                ['ventas@halcon.mx',  'ventas123',  'Ventas'],
                ['compras@halcon.mx', 'compras123', 'Compras'],
                ['almacen@halcon.mx', 'almacen123', 'Almacén'],
                ['ruta@halcon.mx',    'ruta12345',  'Ruta'],
            ]
        );
    }
}
