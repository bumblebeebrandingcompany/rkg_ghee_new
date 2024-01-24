<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\User;

class UpdateSalesRepIdInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // get distributors
        $users = User::where('role', 'distributor')->get();
        //  update orders with dist assign_to_sales_rep id where order is order by dist only
        foreach ($users as $user) {
            Order::whereNull('sub_stockist_id')
                ->where('distributor_id', $user->id)
                ->update([
                    'sales_rep_id' => $user->assign_to_sales_rep,
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
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
