<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('quantity')->unsigned();
            $table->bigInteger('buyer_id')->unsigned()->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();

            $table->foreign('buyer_id')
                ->references('id')
                ->on('users');
            $table->foreign('product_id')
                ->references('id')
                ->on('products');

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
        Schema::dropIfExists('transactions');
    }
}
