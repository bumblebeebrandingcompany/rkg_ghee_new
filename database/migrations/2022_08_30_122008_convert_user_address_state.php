<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class ConvertUserAddressState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        DB::statement('UPDATE `users` SET `address_state` = "Tamil Nadu"');

        $products = Product::where('is_disable', 0)->get();

        foreach ($products as $product) {
            DB::table('state_by_points')->insert(
                [
                    'product_id' => $product->id,
                    'state' => 'Tamil Nadu',
                    'points_per_bundle_for_wholesaler' => $product->points_per_bundle_for_wholesaler,
                    'points_per_bundle_for_distributor' => $product->points_per_bundle,
                    'created_at' =>  \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
