<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE EVENT IF NOT EXISTS deactivate_expired_packages
            ON SCHEDULE EVERY 1 MINUTE
            DO
                UPDATE listing_packages 
                SET status = "0" 
                WHERE expiry_date < NOW() 
                  AND status = "1"
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP EVENT IF EXISTS deactivate_expired_packages');
    }
};
