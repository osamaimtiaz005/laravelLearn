<?php

/**
 * ============================================================
 * MANY-TO-MANY — roles table (one side of the pair)
 * ============================================================
 *
 * Example: User ↔ Role
 *
 * TWO-WAY TEST for many-to-many:
 *   Forward:  Can one user have many roles?     True (Admin + Editor)
 *   Backward: Can one role belong to many users? True (many users are Admin)
 *   Both True → MANY-TO-MANY (needs a PIVOT table)
 *
 * Compare:
 *   1-to-many:  orders.user_id on child only
 *   many-to-many: NO user_id on roles; link lives in role_user pivot
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();   // Admin, Editor, Viewer
            $table->string('label')->nullable(); // human title
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
