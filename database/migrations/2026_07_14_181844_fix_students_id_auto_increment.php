<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix: students.id was a plain INT with no AUTO_INCREMENT / PRIMARY KEY.
     * So INSERT without an id failed with:
     *   Field 'id' doesn't have a default value
     *
     * This makes id auto-increment so Student::create([...]) works.
     */
    public function up(): void
    {
        // Make id the primary key and let MySQL generate the next value
        DB::statement('ALTER TABLE students MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');

        // Continue numbering after the highest existing id
        $maxId = (int) DB::table('students')->max('id');
        DB::statement('ALTER TABLE students AUTO_INCREMENT = ' . ($maxId + 1));
    }

    /**
     * Reverse the change (optional / for rollback learning).
     */
    public function down(): void
    {
        // Drop primary key + auto_increment (keeps existing id values)
        DB::statement('ALTER TABLE students MODIFY id INT NOT NULL');
        // Note: removing PRIMARY KEY may need: ALTER TABLE students DROP PRIMARY KEY;
        // Skipped here if other constraints depend on it.
    }
};
