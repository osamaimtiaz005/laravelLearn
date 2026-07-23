<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Pivot Table: team_user
         *
         * How do we know what columns a pivot table needs?
         * -------------------------------------------------
         * 1. The minimal columns in ALL pivot tables:
         *      - [model1]_id (e.g. team_id)
         *      - [model2]_id (e.g. user_id)
         *    Both are FKs to the two related tables.
         * 
         * 2. Unique constraint on the pair:
         *      - You almost always want to prevent duplicate links,
         *        so you add a unique constraint for (team_id, user_id).
         * 
         * 3. Timestamps (optional, but recommended):
         *      - $table->timestamps() allows you to track when memberships are created or updated.
         * 
         * 4. Extra columns (optional):
         *      - Add columns only if you need to record additional data about the relationship
         *        (e.g. is_active, invitation_sent_at, role_in_team, etc.).
         *      - If you don't need extra data, keep pivot tables as minimal as possible.
         */
        Schema::create('team_user', function (Blueprint $table) {
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps(); // To track when user joined the team, etc.

            $table->unique(['team_id', 'user_id']); // Ensure unique membership pairs

            // Example: If you need to store extra info on the link, add here:
            // $table->boolean('is_active')->default(true);
            // $table->string('role_in_team')->nullable();
            // $table->timestamp('invitation_accepted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
    }
};
