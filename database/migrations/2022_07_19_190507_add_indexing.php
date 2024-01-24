<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distributor_targets', function (Blueprint $table) {
            $table->Index('distributor_id');
            $table->Index('end_date');
            $table->Index('start_date');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->Index('distributor_id');
            $table->Index('sales_rep_id');
        });

        Schema::table('order_lines', function (Blueprint $table) {
            $table->Index('order_id');
        });



        Schema::table('points', function (Blueprint $table) {
            $table->Index('user_id');
            $table->Index('points_for_id');
        });

        Schema::table('shops', function (Blueprint $table) {
            $table->Index('assigned_distributor_id');
            $table->Index('created_by');
        });

        Schema::table('shop_visits', function (Blueprint $table) {
            $table->Index('shop_id');
            $table->Index('sales_rep_id');
        });


        Schema::table('users', function (Blueprint $table) {
            $table->Index('assign_to_sales_rep');
            $table->Index('assign_to_areamanager');
            $table->Index('company_name');
        });
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
