<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\DistributorTarget;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class UpdateTarget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = User::whereIn('role', ['distributor', 'wholesaler', 'sub_stockist'])->get();
        
        foreach($data as $value){

            if($value->role == 'sub_stockist'){
                $total_order = Order::where('sub_stockist_id',$value->id)->whereIn('order_status',['order_delivered', 'order_dispatched'])->whereDate('created_at', '>=', '2022-04-01 00:00:00')
                ->whereDate('created_at', '<=', '2023-03-31 23:59:00')->sum('total_weight');
            }else{
                $total_order = Order::where('distributor_id',$value->id)->whereIn('order_status',['order_delivered', 'order_dispatched'])->whereDate('created_at', '>=', '2022-04-01 00:00:00')
                ->whereDate('created_at', '<=', '2023-03-31 23:59:00')->sum('total_weight');
            }

                $increment = ($total_order * 15/100);
                $after_increment = $total_order + $increment;

                DistributorTarget::where('distributor_id', $value->id)->where('start_date', '2023-04-01')->where('end_date', '2024-03-31')->update([
                    'target_tonnage' => $after_increment/ 1000,
                ]);
    
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
