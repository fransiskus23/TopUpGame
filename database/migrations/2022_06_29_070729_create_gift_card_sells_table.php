<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_card_sells', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('gift_card_service_id')->nullable();
            $table->integer('gift_card_id')->nullable();
            $table->decimal('price')->default(0);
            $table->decimal('discount')->default(0);
            $table->string('code')->nullable();
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
        Schema::dropIfExists('gift_card_sells');
    }
}
