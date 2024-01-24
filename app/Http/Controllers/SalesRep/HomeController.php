<?php

namespace App\Http\Controllers\SalesRep;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ShopVisit;
use App\Models\Shop;
use App\Models\Point;
use App\Models\User;
use App\Models\Order;
use DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){

        $user = auth()->user();

        $now = now();
        $week_1 = ['start' => $now->year . '-' . $now->month . '-1 00:00:00', 
                    'end' => $now->year . '-' . $now->month . '-7 23:59:59'
                    ];
        $week_2 = ['start' => $now->year . '-' . $now->month . '-8 00:00:00', 
                    'end' => $now->year . '-' . $now->month . '-14 23:59:59'
                    ];
        $week_3 = ['start' => $now->year . '-' . $now->month . '-15 00:00:00', 
                    'end' => $now->year . '-' . $now->month . '-21 23:59:59'
                    ];
        $week_4 = ['start' => $now->year . '-' . $now->month . '-22 00:00:00', 
                    'end' => Carbon::now()->endOfMonth()->toDateString().' 23:59:59'
                    ];
       
        
        $shop_visits = ShopVisit::where('sales_rep_id', $user->id)
                        ->select(DB::raw("SUM(IF((visited_at >= '$week_1[start]' AND visited_at <= '$week_1[end]'), 1, 0)) as week_1"),
                            DB::raw("SUM(IF((visited_at >= '$week_2[start]' AND visited_at <= '$week_2[end]'), 1, 0)) as week_2"),
                            DB::raw("SUM(IF((visited_at >= '$week_3[start]' AND visited_at <= '$week_3[end]'), 1, 0)) as week_3"),
                            DB::raw("SUM(IF((visited_at >= '$week_4[start]' AND visited_at <= '$week_4[end]'), 1, 0)) as week_4")
                        )
                        ->first()->toArray();


        $sv_formatted = [];
        $sv_formatted[] = ['Week', 'Visited Shops'];
        foreach ($shop_visits as $key => $value) {
            $sv_formatted[] = [ucwords(str_replace('_', ' ', $key)), (int)$value];
        }
        $sv_formatted = json_encode($sv_formatted);

        $shop_conversion = Shop::where('created_by', $user->id)
                            ->where('sale_convert_status', 'final')
                            ->whereNotNull('sale_status_on')
                            ->select(DB::raw("SUM(IF((sale_status_on >= '$week_1[start]' AND sale_status_on <= '$week_1[end]'), 1, 0)) as week_1"),
                                DB::raw("SUM(IF((sale_status_on >= '$week_2[start]' AND sale_status_on <= '$week_2[end]'), 1, 0)) as week_2"),
                                DB::raw("SUM(IF((sale_status_on >= '$week_3[start]' AND sale_status_on <= '$week_3[end]'), 1, 0)) as week_3"),
                                DB::raw("SUM(IF((sale_status_on >= '$week_4[start]' AND sale_status_on <= '$week_4[end]'), 1, 0)) as week_4")
                            )
                            ->first()->toArray();
        $sc_formatted = [];
        $sc_formatted[] = ['Week', 'Converted Shops'];
        foreach ($shop_conversion as $key => $value) {
            $sc_formatted[] = [ucwords(str_replace('_', ' ', $key)), (int)$value];
        }
        $sc_formatted = json_encode($sc_formatted);


        $recent_conversion = Shop::whereNotNull('sale_status_on')
                                ->where('sale_convert_status', 'final')
                                ->where('created_by', $user->id)
                                ->latest()
                                ->take(5)->get();
        $notifications = $user->notifications;

        //This month stats
        $this_month_visit = ShopVisit::thisMonthVisits($user->id);
        $this_month_conver = Shop::thisMonthConversion($user->id);
        $this_month_points = Point::thisMonthPoint($user->id);

        //Prv month
        $month_st = now()->startOfMonth()->subMonth()->toDateTimeString().' 00:00:00';
        $month_end = now()->endOfMonth()->subMonth()->toDateTimeString().' 23:59:59';

        $prev_month_visit = ShopVisit::visitCounts($user->id, $month_st, $month_end);
        $prev_month_conver = Shop::conversionCounts($user->id, $month_st, $month_end);
        $prev_month_point = Point::thisDatePoint($user->id, $month_st, $month_end);

        $no_of_distributor = User::where('assign_to_sales_rep', $user->id)->count();
        // $points_by_dist = $user->dist_points_sales_rep;

        // find dist id from order 
        $distributors_for_salesrep = Order::where('sales_rep_id', $user->id)
        ->whereIn('order_status', $this->order_statuses_for_points())
        ->pluck('distributor_id')->toArray();
        // all points of sales from dist
        $points_by_dist = Point::whereIn('user_id', $distributors_for_salesrep)->where('points_for', 'orders')->sum('points');

        $dist_point_to_salesrep = Point::thisDatePoint($user->id, null, null, 'distributor');

        return view('sales_rep.home.index')
            ->with(compact('user', 'sv_formatted', 'sc_formatted', 'recent_conversion', 'notifications', 'this_month_visit', 'this_month_conver', 'this_month_points', 'prev_month_visit', 'prev_month_conver', 'prev_month_point', 'no_of_distributor', 'points_by_dist', 'dist_point_to_salesrep'));
    }

    public function contacts(){

        $user = auth()->user();

        $type = Request()->type;


        $distributors = User::where('role', $type)
                        ->where('assign_to_sales_rep', $user->id)
                        ->get();

        return view('sales_rep.home.distributors')
                ->with(compact('distributors', 'type'));
    }
}
