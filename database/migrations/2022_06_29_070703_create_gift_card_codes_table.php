<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('gift_card_codes', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('gift_card_id')->references('id')->on('gift_cards')->constrained()->onDelete('cascade');
//            $table->foreignId('gift_card_service_id')->references('id')->on('gift_card_services')->constrained()->onDelete('cascade');
//            $table->string('code');
//            $table->tinyInteger('status')->default(0)->comment('0=> deactive, 1=> active,2=> used');
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_card_codes');
    }
}
