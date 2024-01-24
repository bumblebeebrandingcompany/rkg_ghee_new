<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Point;

class UpdatePointsToSalesRep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $sales_rep =  User::where('role', 'sales_rep')->get();

        // foreach ($sales_rep as $sale) {
        //     $distributors_for_salesrep = User::where('role', 'distributor')
        //         ->where('assign_to_sales_rep', $sale->id)
        //         ->pluck('id')->toArray();

        //     $total_points = Point::whereIn('user_id', $distributors_for_salesrep)->where('points_for', 'orders')->sum('points');

        //     $points = ($total_points / 1000) * 50;

        //     $sale_total_points = Point::where('user_id', $sale->id)->where('points_for', 'distributor')->sum('points');

        //     if ($points != $sale_total_points) {

        //         $addpoints =  $points - $sale_total_points;

        //         $point = new Point();
        //         $point->add_point($sale, 'distributor', $addpoints, 'add', null, false);
        //         User::where('id', $sale->id)->update([
        //             'dist_points_sales_rep' => $total_points,
        //         ]);
        //         // $user, $points_for, $points, $operation, $points_for_id = null, $notification

        //         // $point = new Point;
        //         // $point->points_for_id = null;
        //         // $point->user_id = $sale->id;
        //         // $point->points_for = 'distributor';
        //         // $point->points = $addpoints;
        //         // $point->operation = 'add';
        //         // $point->save();
        //     }
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_rep', function (Blueprint $table) {
            //
        });
    }
}
