<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    /**
     * Get the visits for the shop
     */
    public function visits()
    {
        return $this->hasMany(ShopVisit::class);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getGstRegisteredStringAttribute()
    {
        return ($this->gst_registered == 1) ? "Registered" : "Not Registered";
    }

    public function thisMonthConversion($sales_rep_id){
        $month_st = now()->startOfMonth()->toDateTimeString();
        $month_end = now()->endOfMonth()->toDateTimeString();

        $convert = Shop::where('created_by', $sales_rep_id)
                        ->where('sale_convert_status', 'final')
                        ->where('sale_status_on', '>=', $month_st)
                        ->where('sale_status_on', '<=', $month_end)
                        ->count();

        return $convert;
    }

    public function conversionCounts($sales_rep_id, $start_date, $end_date){
        $convert = Shop::where('created_by', $sales_rep_id)
                        ->where('sale_convert_status', 'final')
                        ->where('sale_status_on', '>=', $start_date)
                        ->where('sale_status_on', '<=', $end_date)
                        ->count();

        return $convert;
    }
}
