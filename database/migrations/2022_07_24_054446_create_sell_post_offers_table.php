<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellPostOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('sell_post_offers', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('user_id')->references('id')->on('users');
//            $table->foreignId('sell_post_id')->references('id')->on('sell_posts')->constrained()->onDelete('cascade')->comment("sell_post_table");
//            $table->boolean('status')->default(0);
//            $table->dateTime('attempt_at')->nullable();
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
        Schema::dropIfExists('sell_post_offers');
    }
}
