<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('order_number')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('sub_total')->nullable();
            $table->text('delivery_charge')->nullable();
            $table->text('cod_delivery_charge')->nullable();
            $table->text('discount')->nullable();
            $table->text('grand_total')->nullable();
            $table->text('payment_method')->comment('1=COD, 2=Online')->nullable();
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
            $table->text('status')->default('1')->comment('1=Pending, 2=Approved, 3=Processed, 4=Shipping, 5=Delivered')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
