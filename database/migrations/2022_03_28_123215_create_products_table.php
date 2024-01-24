<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('pack_type')->comment('TIN, JAR, POUCH, SACHET');
            $table->integer('pack_size');
            $table->string('pack_size_unit');
            $table->integer('pcs_per_bundle');
            $table->decimal('price_per_bundle')->comment('price per Carton');
            $table->decimal('weight_per_bundle')->comment('Weights per Carton');
            $table->integer('points_per_bundle')->comment('Loyalty Points per Carton');
            $table->string('barcodes');
            $table->string('product_type');
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
