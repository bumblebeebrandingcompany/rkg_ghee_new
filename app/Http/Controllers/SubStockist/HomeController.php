<?php

namespace App\Http\Controllers\SubStockist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Point;
use App\Models\Reward;
use Yajra\Datatables\Datatables;
use DB;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Models\DistributorTarget;


class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $current_fy_date = $this->get_current_fy();
        
        //calculate earned point & total weight
        $order = Order::where('sub_stockist_id', $user->id)
                    ->whereIn('order_status', $this->order_statuses_for_points())
                    ->whereDate('created_at', '>=', $current_fy_date['start'])
                    ->whereDate('created_at', '<=', $current_fy_date['end'])
                    ->select(DB::raw('SUM(total_weight) as total_weight'),
                        DB::raw('SUM(total_price) as total_price')
                    )
                    ->first();


        $order->points_earned = Point::where('user_id', $user->id)
                                ->whereDate('created_at', '>=', $current_fy_date['start'])
                                ->whereDate('created_at', '<=', $current_fy_date['end'])
                                ->sum('points'); 



        //get target tonnage for current FY
        $target_tonnage = DistributorTarget::where('distributor_id', $user->id)
                            ->whereDate('start_date', '>=', $current_fy_date['start'])
                            ->whereDate('end_date', '<=', $current_fy_date['end'])
                            ->first()
                            ->target_tonnage;

        $charts_data = $this->distributor_chart_data($user->id);

        $rewards =  Reward::available_rewards($user);


        $notifications = $user->notifications;

        return view('sub_stockist.home.index')
            ->with(compact('user', 'current_fy_date', 'rewards', 'order', 'target_tonnage', 'charts_data', 'notifications'));

    }
    public function distributor_chart_data($distributor_id){

        $fy4 = $this->get_current_fy(4);
        $fy3 = $this->get_current_fy(3);
        $fy2 = $this->get_current_fy(2);
        $fy1 = $this->get_current_fy(1);
        $fy0 = $this->get_current_fy();

        $output = Order::where('sub_stockist_id', $distributor_id)
                    ->whereIn('order_status', $this->order_statuses_for_points())
                    ->select(
                        // DB::raw("SUM(IF((created_at >= '$fy0[start]' AND created_at <= '$fy0[end]'), total_points, 0)) as fy0_reward"),
                        // DB::raw("SUM(IF(created_at >= '$fy1[start]' AND created_at <= '$fy1[end]', total_points, 0)) as fy1_reward"),
                        // DB::raw("SUM(IF(created_at >= '$fy2[start]' AND created_at <= '$fy2[end]', total_points, 0)) as fy2_reward"),
                        // DB::raw("SUM(IF(created_at >= '$fy3[start]' AND created_at <= '$fy3[end]', total_points, 0)) as fy3_reward"),
                        // DB::raw("SUM(IF(created_at >= '$fy4[start]' AND created_at <= '$fy4[end]', total_points, 0)) as fy4_reward"),

                        DB::raw("SUM(IF((created_at >= '$fy0[start]' AND created_at <= '$fy0[end]'), total_weight, 0)) as fy0_weight"),
                        DB::raw("SUM(IF(created_at >= '$fy1[start]' AND created_at <= '$fy1[end]', total_weight, 0)) as fy1_weight"),
                        DB::raw("SUM(IF(created_at >= '$fy2[start]' AND created_at <= '$fy2[end]', total_weight, 0)) as fy2_weight"),
                        DB::raw("SUM(IF(created_at >= '$fy3[start]' AND created_at <= '$fy3[end]', total_weight, 0)) as fy3_weight"),
                        DB::raw("SUM(IF(created_at >= '$fy4[start]' AND created_at <= '$fy4[end]', total_weight, 0)) as fy4_weight")
                    )
                    ->first();

                    //calulate the dist point from points
        $output_point = Point::where('user_id', $distributor_id)
                        ->select(
                        DB::raw("SUM(IF((created_at >= '$fy0[start]' AND created_at <= '$fy0[end]'), points, 0)) as fy0_reward"),
                        DB::raw("SUM(IF(created_at >= '$fy1[start]' AND created_at <= '$fy1[end]', points, 0)) as fy1_reward"),
                        DB::raw("SUM(IF(created_at >= '$fy2[start]' AND created_at <= '$fy2[end]', points, 0)) as fy2_reward"),
                        DB::raw("SUM(IF(created_at >= '$fy3[start]' AND created_at <= '$fy3[end]', points, 0)) as fy3_reward"),
                        DB::raw("SUM(IF(created_at >= '$fy4[start]' AND created_at <= '$fy4[end]', points, 0)) as fy4_reward"),
                    )
                    ->first();            


        
        $reward_points_data[] = ["Year", "Reward Points", [ 'role' => "style" ] ];
        $reward_points_data[] = [Carbon::create($fy4['start'])->format('Y') . '-' . Carbon::create($fy4['end'])->format('Y'), (float)$output_point->fy4_reward, '#4F1311'];
        $reward_points_data[] = [Carbon::create($fy3['start'])->format('Y') . '-' . Carbon::create($fy3['end'])->format('Y'), (float)$output_point->fy3_reward, '#3F1311'];
        $reward_points_data[] = [Carbon::create($fy2['start'])->format('Y') . '-' . Carbon::create($fy2['end'])->format('Y'), (float)$output_point->fy2_reward, '#2F1211'];
        $reward_points_data[] = [Carbon::create($fy1['start'])->format('Y') . '-' . Carbon::create($fy1['end'])->format('Y'), (float)$output_point->fy1_reward, '#1F1111'];
        $reward_points_data[] = [Carbon::create($fy0['start'])->format('Y') . '-' . Carbon::create($fy0['end'])->format('Y'), (float)$output_point->fy0_reward, '#0F0000'];

        $weight_data[] = ["Year", "Tonnage", [ 'role' => "style" ] ];
        $weight_data[] = [Carbon::create($fy4['start'])->format('Y') . '-' . Carbon::create($fy4['end'])->format('Y'), (float)$output->fy4_weight/1000, '#4F1311'];
        $weight_data[] = [Carbon::create($fy3['start'])->format('Y') . '-' . Carbon::create($fy3['end'])->format('Y'), (float)$output->fy3_weight/1000, '#3F1311'];
        $weight_data[] = [Carbon::create($fy2['start'])->format('Y') . '-' . Carbon::create($fy2['end'])->format('Y'), (float)$output->fy2_weight/1000, '#2F1211'];
        $weight_data[] = [Carbon::create($fy1['start'])->format('Y') . '-' . Carbon::create($fy1['end'])->format('Y'), (float)$output->fy1_weight/1000, '#1F1111'];
        $weight_data[] = [Carbon::create($fy0['start'])->format('Y') . '-' . Carbon::create($fy0['end'])->format('Y'), (float)$output->fy0_weight/1000, '#0F0000'];

        return ['reward_points_data' => json_encode($reward_points_data), 'weight_data' => json_encode($weight_data)];
    }
}
