<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopVisit extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function thisMonthVisits($sales_rep_id){

        $month_st = now()->startOfMonth()->toDateTimeString().' 00:00:00';
        $month_end = now()->endOfMonth()->toDateTimeString().' 23:59:59';

        $visits = ShopVisit::where('sales_rep_id', $sales_rep_id)
                        ->where('visited_at', '>=', $month_st)
                        ->where('visited_at', '<=', $month_end)
                        ->count();

        return $visits;
    }

    public function visitCounts($sales_rep_id, $start_date, $end_date){
        $visits = ShopVisit::where('sales_rep_id', $sales_rep_id)
                        ->where('visited_at', '>=', $start_date)
                        ->where('visited_at', '<=', $end_date)
                        ->count();

        return $visits;
    }
}
