<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fleet', function (Blueprint $table) {
            $table->integer('vehicle_no')->nullable()->change();
            $table->string('manufacturing_year')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('fleet', function (Blueprint $table) {
            $table->integer('vehicle_no')->nullable(false)->change();
            $table->string('manufacturing_year')->nullable(false)->change();
        });
    }
};
