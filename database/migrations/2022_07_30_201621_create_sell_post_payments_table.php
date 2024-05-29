<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellPostPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_post_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('sell_post_id')->nullable();
            $table->decimal('price')->default(0);
            $table->decimal('discount')->default(0);
            $table->tinyInteger('status')->default(0)->comment('1=> Complete');
            $table->tinyInteger('payment_status')->default(0)->comment('1=> active, 2=> rejected');
            $table->string('transaction',50)->nullable();
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
        Schema::dropIfExists('sell_post_payments');
    }
}
