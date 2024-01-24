<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact');
            $table->string('reference_id');
            $table->string('sale_convert_status')->nullable()->default(null)->index();
            $table->dateTime('sale_status_on')->nullable()->default(null);
            $table->string('location');
            $table->string('pin_code');
            $table->boolean('gst_registered');
            $table->string('gst_number')->nullable();
            // $table->string('pan_number')->nullable();
            $table->string('existing_ghee_products')->nullable();
            $table->string('type_of_client');

            $table->string('gst_certificate')->nullable();
            $table->string('decline_reason')->nullable();
            $table->string('reason_desc')->nullable();
            // $table->string('pan_certificate')->nullable();

            $table->foreignId('assigned_distributor_id')->nullable()->comment('user id of distributor');
            
            $table->foreignId('created_by')->comment('Sales rep user id');

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
        Schema::dropIfExists('shops');
    }
}
