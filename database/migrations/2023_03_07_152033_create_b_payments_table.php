<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_payments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('user')->unsigned();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('bill')->unsigned()->nullable();
            $table->foreign('bill')->references('id')->on('bills')->onDelete('cascade');
            $table->integer('amount')->unsigned();
            $table->date('date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_payments');
    }
}
