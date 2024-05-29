<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('voucher_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->references('id')->on('vouchers')->constrained()->onDelete('cascade');
            $table->foreignId('voucher_service_id')->references('id')->on('voucher_services')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->tinyInteger('status')->default(0)->comment('0=> deactive, 1=> active,2=> used');
            $table->timestamps();
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_codes');
    }
}
