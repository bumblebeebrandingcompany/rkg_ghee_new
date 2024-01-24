<?php

namespace App\Http\Controllers\Dist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Point;
use App\Models\User;
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
        $order = Order::where('distributor_id', $user->id)
            ->whereIn('order_status', $this->order_statuses_for_points())
            ->whereDate('created_at', '>=', $current_fy_date['start'])
            ->whereDate('created_at', '<=', $current_fy_date['end'])
            ->select(
                DB::raw('SUM(total_weight) as total_weight'),
                DB::raw('SUM(total_price) as total_price')
            )
            ->first();
        $order->points_earned = Point::where('user_id', $user->id)->where('operation', 'add')
            ->whereDate('created_at', '>=', $current_fy_date['start'])
            ->whereDate('created_at', '<=', $current_fy_date['end'])
            ->sum('points') - Point::where('user_id', $user->id)->where('operation', 'sub')
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

        return view('dist.home.index')
            ->with(compact('user', 'current_fy_date', 'rewards', 'order', 'target_tonnage', 'charts_data', 'notifications'));
    }

    public function distributor_chart_data($distributor_id)
    {

        $fy4 = $this->get_current_fy(4);
        $fy3 = $this->get_current_fy(3);
        $fy2 = $this->get_current_fy(2);
        $fy1 = $this->get_current_fy(1);
        $fy0 = $this->get_current_fy();

        $output = Order::where('distributor_id', $distributor_id)
            ->whereIn('order_status', $this->order_statuses_for_points())
            ->select(
                // DB::raw("SUM(IF((created_at >= '$fy0[start]' AND created_at <= '$fy0[end]'), total_points, 0)) as fy0_reward"),
                // DB::raw("SUM(IF(created_at >= '$fy1[start]' AND created_at <= '$fy1[end]', total_points, 0)) as fy1_reward"),
                // DB::raw("SUM(IF(created_at >= '$fy2[start]' AND created_at <= '$fy2[end]', total_points, 0)) as fy2_reward"),
                // DB::raw("SUM(IF(created_at >= '$fy3[start]' AND created_at <= '$fy3[end]', total_points, 0)) as fy3_reward"),
                // DB::raw("SUM(IF(created_at >= '$fy4[start]' AND created_at <= '$fy4[end]', total_points, 0)) as fy4_reward"),

                DB::raw("SUM(IF((created_at >= '$fy0[start]' AND created_at <= '$fy0[end]:23:59:00'), total_weight, 0)) as fy0_weight"),
                DB::raw("SUM(IF(created_at >= '$fy1[start]' AND created_at <= '$fy1[end]:23:59:00', total_weight, 0)) as fy1_weight"),
                DB::raw("SUM(IF(created_at >= '$fy2[start]' AND created_at <= '$fy2[end]:23:59:00', total_weight, 0)) as fy2_weight"),
                DB::raw("SUM(IF(created_at >= '$fy3[start]' AND created_at <= '$fy3[end]:23:59:00', total_weight, 0)) as fy3_weight"),
                DB::raw("SUM(IF(created_at >= '$fy4[start]' AND created_at <= '$fy4[end]:23:59:00', total_weight, 0)) as fy4_weight")
            )
            ->first();

        //calulate the dist point from points
        $output_point = Point::where('user_id', $distributor_id)
            ->select(
                DB::raw("SUM(CASE WHEN operation = 'add' AND created_at >= '$fy0[start]' AND created_at <= '$fy0[end]:23:59:00' then points
                WHEN operation = 'sub' AND created_at >= '$fy0[start]' AND created_at <= '$fy0[end]:23:59:00' then -points END) as fy0_reward"),

                DB::raw("SUM(CASE WHEN operation = 'add' AND created_at >= '$fy1[start]' AND created_at <= '$fy1[end]:23:59:00' then points
                WHEN operation = 'sub' AND created_at >= '$fy1[start]' AND created_at <= '$fy1[end]:23:59:00' then -points END) as fy1_reward"),

                DB::raw("SUM(CASE WHEN operation = 'add' AND created_at >= '$fy2[start]' AND created_at <= '$fy2[end]:23:59:00' then points
                WHEN operation = 'sub' AND created_at >= '$fy2[start]' AND created_at <= '$fy2[end]:23:59:00' then -points END) as fy2_reward"),

                DB::raw("SUM(CASE WHEN operation = 'add' AND created_at >= '$fy3[start]' AND created_at <= '$fy3[end]:23:59:00' then points
                WHEN operation = 'sub' AND created_at >= '$fy3[start]' AND created_at <= '$fy3[end]:23:59:00' then -points END) as fy3_reward"),

                DB::raw("SUM(CASE WHEN operation = 'add' AND created_at >= '$fy4[start]' AND created_at <= '$fy4[end]:23:59:00' then points
                WHEN operation = 'sub' AND created_at >= '$fy4[start]' AND created_at <= '$fy4[end]:23:59:00' then -points END) as fy4_reward"),

                // DB::raw("SUM(IF(created_at >= '$fy1[start]' AND created_at <= '$fy1[end]', points, 0)) as fy1_reward"),
                // DB::raw("SUM(IF(created_at >= '$fy2[start]' AND created_at <= '$fy2[end]', points, 0)) as fy2_reward"),
                // DB::raw("SUM(IF(created_at >= '$fy3[start]' AND created_at <= '$fy3[end]', points, 0)) as fy3_reward"),
                // DB::raw("SUM(IF(created_at >= '$fy4[start]' AND created_at <= '$fy4[end]', points, 0)) as fy4_reward"),
            )
            ->first();



        $reward_points_data[] = ["Year", "Reward Points", ['role' => "style"]];
        $reward_points_data[] = [Carbon::create($fy4['start'])->format('Y') . '-' . Carbon::create($fy4['end'])->format('Y'), (float)$output_point->fy4_reward, '#4F1311'];
        $reward_points_data[] = [Carbon::create($fy3['start'])->format('Y') . '-' . Carbon::create($fy3['end'])->format('Y'), (float)$output_point->fy3_reward, '#3F1311'];
        $reward_points_data[] = [Carbon::create($fy2['start'])->format('Y') . '-' . Carbon::create($fy2['end'])->format('Y'), (float)$output_point->fy2_reward, '#2F1211'];
        $reward_points_data[] = [Carbon::create($fy1['start'])->format('Y') . '-' . Carbon::create($fy1['end'])->format('Y'), (float)$output_point->fy1_reward, '#1F1111'];
        $reward_points_data[] = [Carbon::create($fy0['start'])->format('Y') . '-' . Carbon::create($fy0['end'])->format('Y'), (float)$output_point->fy0_reward, '#0F0000'];

        $weight_data[] = ["Year", "Tonnage", ['role' => "style"]];
        $weight_data[] = [Carbon::create($fy4['start'])->format('Y') . '-' . Carbon::create($fy4['end'])->format('Y'), (float)$output->fy4_weight / 1000, '#4F1311'];
        $weight_data[] = [Carbon::create($fy3['start'])->format('Y') . '-' . Carbon::create($fy3['end'])->format('Y'), (float)$output->fy3_weight / 1000, '#3F1311'];
        $weight_data[] = [Carbon::create($fy2['start'])->format('Y') . '-' . Carbon::create($fy2['end'])->format('Y'), (float)$output->fy2_weight / 1000, '#2F1211'];
        $weight_data[] = [Carbon::create($fy1['start'])->format('Y') . '-' . Carbon::create($fy1['end'])->format('Y'), (float)$output->fy1_weight / 1000, '#1F1111'];
        $weight_data[] = [Carbon::create($fy0['start'])->format('Y') . '-' . Carbon::create($fy0['end'])->format('Y'), (float)$output->fy0_weight / 1000, '#0F0000'];

        return ['reward_points_data' => json_encode($reward_points_data), 'weight_data' => json_encode($weight_data)];
    }

    public function show()
    {

        $user = auth()->user();

        if (request()->ajax()) {


            $shops = Shop::leftjoin('users as distributor', 'assigned_distributor_id', '=', 'distributor.id')
                ->leftjoin('users as sales_rep', 'created_by', '=', 'sales_rep.id')
                ->select(['shops.*', 'distributor.name as distributor_name', 'sales_rep.name as sales_rep_name']);


            if ($user->role == 'distributor') {
                $shops->where('assigned_distributor_id', $user->id);
            }

            return DataTables::of($shops)

                ->addColumn('action', function ($row) use ($user) {
                    if ($row->sale_convert_status == 'pending_for_distributor') {
                        $html = '<span class="badge badge-success m-1">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        if ($user->role == 'distributor') {
                            $html .= '<a class="btn btn-sm btn-primary m-1"
                                href="' . route("dist.approve_sales", [$row->id]) . '">
                                Accept
                            </a><a data-toggle="modal" onClick="putid(' . $row->id . ')" data-target="#exampleModalCenter" id="decline_btn" class="btn btn-sm btn-danger m-1">
                                Decline
                            </a>';
                        }
                    } else {

                        if ($row->sale_convert_status == 'decline_by_distributor') {
                            $html = '<span class="badge badge-danger m-1">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                            if ($row->decline_reason == 'other') {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->reason_desc)) . '</p>';
                            } else {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->decline_reason)) . '</p>';
                            }
                        } else {

                            $html = '<span class="badge badge-primary m-1">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        }
                    }
                    return $html;
                })
                ->addColumn(
                    'visited_at',
                    function ($row) {
                        return $row->visited_at;
                    }
                )
                ->rawColumns(['action', 'visited_at'])
                ->make(true);
        }

        return view('dist.home.shops')
            ->with(compact('user'));
    }

    public function approve_sales($id)
    {


    

        $user = auth()->user();

        if ($user->role != 'distributor' && $user->role != 'sub_stockist') {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            $shop = Shop::findorfail($id);
            if ($shop->sale_convert_status != 'pending_for_distributor' && $shop->sale_convert_status != 'pending_for_sub_stockist') {
                abort(403, 'Unauthorized action.');
            }


            if($user->role == 'sub_stockist'){

               
                $shop->sale_convert_status = 'final';
                $sales_rep = User::find($shop->created_by);
                $this->__add_converted_reward_points($sales_rep,$shop->id );
            }else{
                $shop->sale_convert_status = 'pending_for_areamanager';
            }

            
            $shop->update();



            DB::commit();

            return back()
                ->with('status', 'Successfully Accepted');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
            return back()
                ->with('status', $output);
        }
    }

    public function decline_reason(Request $request)
    {

        $user = auth()->user();

        if ($user->role != 'distributor' && $user->role != 'sub_stockist') {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {

            $shop = Shop::findorfail($request->get('id'));
            if ($shop->sale_convert_status != 'pending_for_distributor' && $shop->sale_convert_status != 'pending_for_sub_stockist') {
                abort(403, 'Unauthorized action.');
            }


            
            $shop->sale_convert_status = 'decline_by_'.$user->role.'';


            $shop->decline_reason = $request->get('reason');
            $shop->reason_desc = $request->get('discribtion');
            $shop->update();
            DB::commit();

            return back()
                ->with('status', 'Successfully Decline');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
            return back()
                ->with('status', $output);
        }
    }

    private function __add_converted_reward_points($sales_rep, $id)
    {
        //60 shop visits  + 2 shop conversions per month to claim the 150 reward points

        $convert_count = Shop::thisMonthConversion($sales_rep->id);


        if ($convert_count >= 2) {
            //200 points for first 2, then 100 each
            $points = ($convert_count == 2) ? 200 : 100;
            $point = new Point();
            $point->add_point($sales_rep, 'converted', $points, 'add', $id,  true);
            return true;
        }

        return false;
    }
}
