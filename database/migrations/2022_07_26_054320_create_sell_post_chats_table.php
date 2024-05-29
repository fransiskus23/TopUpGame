<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellPostChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('sell_post_chats', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('sell_post_id')->references('id')->on('sell_posts')->onDelete('cascade');
//            $table->morphs('chat');
//            $table->longText('description')->nullable();
//            $table->boolean('is_read')->default(0);
//            $table->boolean('is_read_admin')->default(0);
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
        Schema::dropIfExists('sell_post_chats');
    }
}
