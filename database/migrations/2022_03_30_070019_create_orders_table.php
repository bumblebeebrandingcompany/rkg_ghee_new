<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id')->nullable()->unique();
            $table->string('invoice_no')->nullable();
            $table->string('invoice_file_name')->nullable();
            $table->string('order_status')->comment('draft, order_sent');

            $table->foreignId('distributor_id')->comment('user id of distributor');
            $table->foreignId('sales_rep_id')->nullable()->comment('user id of sales representative placing order');
            $table->text('distributor_notes')->nullable();
            $table->integer('total_points')->default(0);
            $table->decimal('total_weight')
                ->default(0);
            $table->decimal('total_price')
                ->default(0);     
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
