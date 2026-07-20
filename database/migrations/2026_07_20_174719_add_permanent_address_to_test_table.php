<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run when you: php artisan migrate
     * Adds permanent_address to the test table.
     */
    public function up(): void
    {
        Schema::table('test', function (Blueprint $table) {
            $table->string('permanent_address');
        });
    }

    /**
     * Run when you: php artisan migrate:rollback
     * Must undo what up() did — otherwise the column stays in the DB
     * while Laravel forgets this migration ran (then remigrate fails).
     */
    public function down(): void
    {
        Schema::table('test', function (Blueprint $table) {
            $table->dropColumn('permanent_address');
        });
    }
};
