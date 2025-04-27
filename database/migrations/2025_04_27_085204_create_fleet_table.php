<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This method is used to define the structure of your database table.
     */
    public function up(): void
    {
        Schema::create('fleet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('vehicle_no')->unique(); 
            $table->string('vehicle_name');
            $table->string('vehicle_owner_name');
            $table->string('registration_date');
            $table->string('vehicle_type');
            $table->string('license_plate')->unique();
            $table->string('manufacturing_year');
            $table->enum('status', ['active', 'inactive', 'under_maintenance'])->default('active');
            $table->integer('mileage')->nullable();
            $table->string('fuel_type')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * This method is used to drop the table in case of rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet');  // Drop the fleet table if it exists
    }
};
