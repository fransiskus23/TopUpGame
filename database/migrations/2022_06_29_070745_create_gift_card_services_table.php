<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('gift_card_services', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('gift_card_id')->constrained('gift_cards')->onDelete('cascade');
//
//            $table->string('name')->nullable();
//            $table->decimal("price")->default(0.00);
//            $table->boolean("status")->default(1);
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
        Schema::dropIfExists('gift_card_services');
    }
}
