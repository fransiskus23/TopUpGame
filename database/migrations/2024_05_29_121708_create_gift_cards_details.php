<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_card_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_cards_id')->references('id')->on('gift_cards')->constrained()->onDelete('cascade');
            $table->integer('language_id')->nullable();
            $table->string('name');
            $table->longText('details');
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
        Schema::dropIfExists('gift_card_details');
    }
}
