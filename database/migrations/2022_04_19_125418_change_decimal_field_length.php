<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeDecimalFieldLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `distributor_targets` CHANGE `target_tonnage` `target_tonnage` DECIMAL(20,4) NOT NULL');

        DB::statement("ALTER TABLE `orders` CHANGE `total_weight` `total_weight` DECIMAL(20,4) NOT NULL DEFAULT '0.00', CHANGE `discount_percent` `discount_percent` DECIMAL(20,4) NOT NULL DEFAULT '0.00', CHANGE `subtotal_amount` `subtotal_amount` DECIMAL(20,4) NOT NULL DEFAULT '0.00', CHANGE `total_price` `total_price` DECIMAL(20,4) NOT NULL DEFAULT '0.00'");

        DB::statement("ALTER TABLE `order_lines` CHANGE `line_price` `line_price` DECIMAL(20,4) NOT NULL DEFAULT '0.00'");
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
