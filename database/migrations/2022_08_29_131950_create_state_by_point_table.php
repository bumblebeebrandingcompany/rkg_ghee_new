<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateByPointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_by_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->comment('id of product table');
            $table->string('state');
            $table->integer('points_per_bundle_for_wholesaler')->nullable()->comment('Loyalty Points per Carton for wholesaler');
            $table->integer('points_per_bundle_for_distributor')->nullable()->comment('Loyalty Points per Carton for distributor');
            $table->timestamps();

            $table->index(['product_id']);
            $table->index(['state']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('state_by_points');
    }
}
