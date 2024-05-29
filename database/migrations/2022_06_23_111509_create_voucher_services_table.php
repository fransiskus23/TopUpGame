<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_vouchers_id')->references('id')->on('game_vouchers')->constrained()->onDelete('cascade');

            $table->string('name')->nullable();
            $table->decimal("price")->default(0.00);
            $table->boolean("status")->default(1);
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
        Schema::dropIfExists('voucher_services');
    }
}
