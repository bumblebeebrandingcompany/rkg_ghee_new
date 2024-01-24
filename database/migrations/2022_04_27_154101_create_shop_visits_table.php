<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id');
            $table->string('visit_proof_selfie')->nullable();
            $table->foreignId('sales_rep_id')->comment('Sales rep user id');
            $table->dateTime('visited_at');
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
        Schema::dropIfExists('shop_visits');
    }
}
