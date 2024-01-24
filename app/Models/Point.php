<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\PointsAdded;

class Point extends Model
{
    use HasFactory;

    /**
     * gives sum of points for this month
     * @param $user_id
     *
     * @return decimal
     */
    public function thisMonthPoint($user_id){
        $month_st = now()->startOfMonth()->toDateTimeString().' 00:00:00';
        $month_end = now()->endOfMonth()->toDateTimeString().' 23:59:59';

        $points = Point::where('user_id', $user_id)
                        ->where('created_at', '>=', $month_st)
                        ->where('created_at', '<=', $month_end)
                        ->sum('points');

        return $points;
    }

    /**
     * gives sum of points for a given date range
     * 
     * @param $user_id
     * @param $start_date (default null)
     * @param $end_date (default null)
     * @param $points_for (default null)
     *
     * @return decimal
     */
    public function thisDatePoint($user_id, $start_date = null, $end_date = null, $points_for = null){
        $points = Point::where('user_id', $user_id);

        if(!is_null($start_date) && !is_null($end_date)){
            $points->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', $end_date);
        }

        if(!is_null($points_for)){
            $points->where('points_for', $points_for);
        }
                        
        $points = $points->sum('points');

        return $points;
    }

    /**
     * create entry for points & also send notification to the user.
     * 
     * @param $user obj
     * @param $points_for string
     * @param $points decimal
     * @param $operation string(add/substract)
     *
     * @return object
     */
    public function add_point($user, $points_for, $points, $operation, $points_for_id = null, $notification){

        $point = new Point;
        $point->user_id = $user->id;
        $point->points_for = $points_for;
        $point->points_for_id = $points_for_id;
        $point->points = $points;
        $point->operation = $operation;
        $point->save();

        if($notification){
            $user->notify(new PointsAdded($point));
        }


        return $point;
    }
}
