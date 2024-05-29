<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_sells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->references('id')->on('sell_post_categories')->constrained()->onDelete('cascade')->comment("Sell_post_categories_table");
            $table->string('title');
            $table->string('price');
            $table->longText('details');
            $table->longText('credential');
            $table->string('image');
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
        Schema::dropIfExists('game_sells');
    }
}
