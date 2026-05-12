<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    private static int $invoiceCounter = 100;

    public function definition(): array
    {
        self::$invoiceCounter++;
        $n = str_pad(self::$invoiceCounter, 3, '0', STR_PAD_LEFT);

        return [
            'invoice_number'  => "FAC-2024-{$n}",
            'customer_number' => 'CLI-' . str_pad(fake()->numberBetween(1, 99), 4, '0', STR_PAD_LEFT),
            'customer_name'   => fake()->company(),
            'rfc'             => strtoupper(fake()->bothify('???######???')),
            'fiscal_regime'   => fake()->randomElement(['Persona Moral', 'Persona Física']),
            'fiscal_address'  => fake()->address(),
            'delivery_address'=> fake()->streetAddress() . ', ' . fake()->city(),
            'notes'           => fake()->optional()->sentence(),
            'status'          => fake()->randomElement(Order::STATUS_ORDER),
            'created_by'      => User::factory(),
        ];
    }

    public function ordered(): static
    {
        return $this->state(['status' => 'ordered']);
    }

    public function inProcess(): static
    {
        return $this->state(['status' => 'in_process']);
    }

    public function inRoute(): static
    {
        return $this->state(['status' => 'in_route']);
    }

    public function delivered(): static
    {
        return $this->state(['status' => 'delivered']);
    }

    public function archived(): static
    {
        return $this->state(['deleted_at' => now()]);
    }
}
