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
            $table->bigInteger('merchant_id');
            $table->string('phone');
            $table->string('room_number');
            $table->decimal('commission');
            $table->decimal('commission_rate', 2);
            $table->decimal('price');
            $table->decimal('deduction')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Normal, 2: Cancel');
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
