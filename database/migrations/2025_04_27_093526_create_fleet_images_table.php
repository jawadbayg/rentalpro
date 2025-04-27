<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetImagesTable extends Migration
{
    public function up()
    {
        Schema::create('fleet_images', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('fleet_id'); 
            $table->longText('image'); 
            $table->timestamps();
            $table->foreign('fleet_id')->references('id')->on('fleets')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fleet_images');
    }
}

