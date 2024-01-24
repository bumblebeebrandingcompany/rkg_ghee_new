<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Point;
use App\Models\Order;

class ConvertPointsIntoPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $order = Order::whereIn('order_status', ['order_dispatched', 'order_delivered'])->get();

       foreach ($order as  $value) {
            $point = new Point;
            $point->points_for_id = $value->id;
            $point->user_id = $value->distributor_id;
            $point->points_for = 'orders';
            $point->points = $value->total_points;
            $point->operation = 'add';
            $point->created_at = $value->updated_at;
            $point->save();
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
