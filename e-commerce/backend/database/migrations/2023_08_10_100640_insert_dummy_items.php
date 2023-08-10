<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = "INSERT INTO `items` 
            (`id`, `name`, `price`, `stock`, `created_at`, `updated_at`)
        VALUES
            (1,'apple',3,100,'2023-08-10 09:00:00','2023-08-10 09:00:00'),
            (2,'orange',2,50,'2023-08-10 09:00:00','2023-08-10 09:00:00'),
            (3,'banana',4,20,'2023-08-10 09:00:00','2023-08-10 09:00:00'),
            (4,'strawberry',3,100,'2023-08-10 09:00:00','2023-08-10 09:00:00'),
            (5,'kiwi',1,80,'2023-08-10 09:00:00','2023-08-10 09:00:00');";
        
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $sql = "DELETE FROM items WHERE id <=5";
        DB::statement($sql);
    }
};
