<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameVoucherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_voucher_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_vouchers_id')->references('id')->on('game_vouchers')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('game_voucher_details');
    }
}
