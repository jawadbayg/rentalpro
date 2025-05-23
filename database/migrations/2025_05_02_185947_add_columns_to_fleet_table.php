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
        Schema::table('fleet', function (Blueprint $table) {
            $table->integer('no_of_seats')->nullable();
            $table->integer('no_of_doors')->nullable();
            $table->integer('no_of_bags')->nullable();
            $table->string('color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fleet', function (Blueprint $table) {
            $table->dropColumn(['no_of_seats', 'no_of_doors', 'no_of_bags', 'color']);
        });
    }
};
