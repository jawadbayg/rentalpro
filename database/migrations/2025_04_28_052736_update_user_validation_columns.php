<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_validation', function (Blueprint $table) {
            $table->bigInteger('identity_number')->change(); 
            $table->string('license_number')->change();    
        });
    }

    public function down()
    {
        Schema::table('user_validation', function (Blueprint $table) {
            $table->integer('identity_number')->change();  // Revert back to integer
            $table->integer('license_number')->change();   // Revert back to integer
        });
    }

};
