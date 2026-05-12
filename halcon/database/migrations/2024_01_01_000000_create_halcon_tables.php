<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ─────────────────────────────────────────────
// Migration 1: Create roles table
// ─────────────────────────────────────────────
return new class extends Migration
{
    public function up(): void
    {
        // ROLES
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // USERS (override default)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // ORDERS
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Invoice & Customer
            $table->string('invoice_number')->unique();
            $table->string('customer_number');
            $table->string('customer_name');

            // Fiscal data
            $table->string('rfc')->nullable();
            $table->string('fiscal_regime')->nullable();
            $table->text('fiscal_address')->nullable();

            // Logistics
            $table->text('delivery_address');
            $table->text('notes')->nullable();

            // Status lifecycle
            $table->enum('status', ['ordered', 'in_process', 'in_route', 'delivered'])
                  ->default('ordered');

            // Evidence photos
            $table->string('route_photo')->nullable();
            $table->string('delivery_photo')->nullable();

            // Relationships
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Soft delete
            $table->softDeletes();
            $table->timestamps();

            // Indexes for search
            $table->index('invoice_number');
            $table->index('customer_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
