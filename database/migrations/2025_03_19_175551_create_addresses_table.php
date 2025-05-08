<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->text('full_name')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('alternate_phone_number')->nullable();
            $table->text('pin_code')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            
            $table->text('state')->nullable();
            $table->text('city')->nullable();
            $table->text('house_and_building')->nullable();
            $table->text('area_address')->nullable();
            $table->text('landmark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
