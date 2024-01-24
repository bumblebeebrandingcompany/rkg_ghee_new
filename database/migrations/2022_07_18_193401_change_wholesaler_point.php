<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\User;
use App\Models\Point;

class ChangeWholesalerPoint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $orders = Order::get();

        foreach($orders as $order){

            if(User::find($order->distributor_id)->role == 'wholesaler'){
               
                $orderLines = OrderLine::where('order_id', $order->id)->get();

                foreach($orderLines as $orderLine ){

                    // calculate points on wholesaler points basis 
                    $points = Product::find($orderLine->product_id)->points_per_bundle_for_wholesaler * $orderLine->quantity;
                    
                    // update each order line accoding to new points
                    OrderLine::where('id', $orderLine->id)->update([
                        'points_earned'=> $points
                    ]);
                }

              
                // update date order 
                Order::where('id', $order->id)->update([
                    'total_points'=> OrderLine::where('order_id', $order->id)->sum('points_earned'),
                ]);
                
                 // update point on point table
                Point::where('points_for_id', $order->id)->where('points_for', 'orders')->update([
                    'points'=> OrderLine::where('order_id', $order->id)->sum('points_earned')
                ]);
            }
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
