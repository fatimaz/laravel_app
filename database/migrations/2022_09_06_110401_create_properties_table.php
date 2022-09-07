<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('organisation');
            $table->string('property_type');
            $table->integer('parent_property_id')->nullable();
            $table->integer('uprn');
            $table->text('address');
            $table->string('town');
            $table->string('postcode');
            $table->boolean('live');
            $table->timestamps();
            $table->softDeletes();  
            
        });
  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
