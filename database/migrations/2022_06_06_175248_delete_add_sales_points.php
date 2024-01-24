<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Point;
use App\Models\User;
use App\Models\Order;
use App\Notifications\PointsAdded;

class DeleteAddSalesPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $point_deletes = Point::where('points_for', 'distributor')->get();
        // delete notification for each points id
        foreach ($point_deletes as $point_delete) {
            DB::table('notifications')->where('type', 'App\Notifications\PointsAdded')->whereJsonContains('data->point_id', $point_delete->id)->delete();
        }
            
        Point::where('points_for', 'distributor')->delete();        

        $user = User::where('role', 'sales_rep')->get();

        foreach ($user as  $value) {
            // distributers for this sales_reps
            $distributor_ids = User::where('role', 'distributor')
                                        ->where('assign_to_sales_rep', $value->id)
                                        ->pluck('id')->toArray();
            // points of distributer for this sales_reps
            $total_points = Point::whereIn('user_id', $distributor_ids)->sum('points');

            // update sales points
            User::where('id', $value->id)->update([
                'dist_points_sales_rep'=> $total_points,
            ]);

            //points calculate.
            $points = (($total_points) / 1000)*50;

        
            if($points > 0){
                //add notification
                $point = new Point;
                $point->user_id = $value->id;
                $point->points_for = 'distributor';
                $point->points = $points;
                $point->operation = 'add';
                $point->save();
                $user_notify = User::find($value->id);
                $user_notify->notify(new PointsAdded($point));
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
