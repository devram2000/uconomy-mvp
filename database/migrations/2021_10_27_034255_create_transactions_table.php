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
            $table->id();
            $table->bigInteger('user')->unsigned();
            $table->integer('amount')->unsigned();
            $table->integer('remaining_balance')->unsigned();
            $table->foreign('user')->references('id')->on('users');
            $table->string('category', 100);
            $table->string('description', 555);
            $table->string('photo', 2048)->nullable();
            $table->timestamp('start_date');
            $table->timestamp('due_date');
            $table->string('suggested_dates', 555)->nullable();
            $table->string('suggested_amounts', 555)->nullable();
            $table->string('zelle', 100);
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
