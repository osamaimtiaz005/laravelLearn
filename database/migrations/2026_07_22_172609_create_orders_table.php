<?php

/**
 * ============================================================
 * MIGRATION: create orders table (ONE-TO-MANY child table)
 * ============================================================
 *
 * TWO-WAY TEST supported by this schema:
 *
 * FORWARD (True):
 *   user_id is NOT unique → one user_id can appear on many order rows
 *   → one user can place many orders
 *
 * BACKWARD (True):
 *   each order row has ONE user_id column (single value)
 *   → one order belongs to only one user
 *
 * If you added ->unique() on user_id, Forward would break
 * (only one order per user = one-to-one style).
 *
 * FK helpers:
 *   constrained('users')   user_id must exist in users.id
 *   cascadeOnDelete()      delete user → delete their orders
 *   nullOnDelete()         needs nullable(); keep order, clear user_id
 *   restrictOnDelete()     block delete user while orders exist
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** up() = php artisan migrate → CREATE table */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();          // primary key of this order
            $table->timestamps();  // created_at, updated_at

            // Foreign key for BOTH directions of the Two-Way Test
            // Forward: same user_id on many rows (no unique)
            // Backward: each row has exactly one user_id
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('description');
            $table->string('price');
        });
    }

    /** down() = php artisan migrate:rollback → DROP table */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
