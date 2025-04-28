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
        Schema::create('user_validation', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('identity_number');
            $table->integer('license_number');
            $table->string('license_provider');
            $table->integer('age');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_validation');
    }
};
