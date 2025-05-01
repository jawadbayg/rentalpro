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
        Schema::table('fp_detail', function (Blueprint $table) {
            $table->string('address')->change(); // Change the address column to string
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fp_detail', function (Blueprint $table) {
            $table->integer('address')->change(); // Revert back to integer if rollback is needed
        });
    }
};
