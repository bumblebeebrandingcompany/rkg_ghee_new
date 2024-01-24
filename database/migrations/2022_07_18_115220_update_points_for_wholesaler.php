<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class UpdatePointsForWholesaler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Product::where('barcodes', 'CS0001')->update([
            'points_per_bundle_for_wholesaler' => 0,
        ]);

        Product::where('barcodes', '!=', 'CS0001')->update([
            'points_per_bundle_for_wholesaler' => 6,
        ]);
    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
