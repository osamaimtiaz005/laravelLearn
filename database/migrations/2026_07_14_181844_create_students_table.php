<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the students table used by:
 * - app/Models/Student.php
 * - resources/views/elqQueryBuilder/studentList.blade.php
 * - resources/views/paginate/list.blade.php
 *
 * Columns match the forms/views: id, name, email, batch
 * (no created_at/updated_at — Student model has $timestamps = false)
 *
 * Replaces the old ALTER-only fix that assumed students already existed.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('name');
            $table->string('email');
            $table->string('batch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
