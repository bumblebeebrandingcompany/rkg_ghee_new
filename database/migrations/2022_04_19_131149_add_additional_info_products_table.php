<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class AddAdditionalInfoProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('additional_info')->after('barcodes')->nullable()->default(null);
        });

        Product::where('id', 27)->update(['additional_info' => '5 Rs Sachet']);
        Product::where('id', 26)->update(['additional_info' => '10 Rs Sachet']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
