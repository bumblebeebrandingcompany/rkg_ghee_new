<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use Illuminate\Support\Facades\DB;



class CopyPoinstsTamilnaduToAndhra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $state = 'Tamil Nadu';
        $products = Product::where('is_disable', 0)->leftjoin('state_by_points as points', function ($join) use ($state) {
            $join->on('points.product_id', '=', 'products.id')
                ->where('points.state', $state);
        })
            ->select(['products.*', 'points.points_per_bundle_for_distributor as distributer_point', 'points.points_per_bundle_for_wholesaler as wholesaler_point'])->get();

        foreach ($products as $product) {
            DB::table('state_by_points')
                ->updateOrInsert(
                    ['product_id' => $product->id, 'state' => 'Andhra Pradesh'],
                    [
                        'points_per_bundle_for_wholesaler' => $product->wholesaler_point, 'points_per_bundle_for_distributor' => $product->distributer_point, 'created_at' =>  \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
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
        Schema::table('andhra', function (Blueprint $table) {
            //
        });
    }
}
