<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use DB;

class HomeController extends Controller
{
	public function index()
	{
		$user = Auth::user();
		
		if ($user->role == 'area_manager') {

			$distributor_ids = User::where('role', 'distributor')
				->where('assign_to_areamanager', $user->id)
				->pluck('id')->toArray();

				

			//show for top  distributors under him by volume
			$distributor_volume = User::select('users.*', DB::raw('SUM(orders.total_weight) As total_weight'))
				->leftJoin('orders', 'orders.distributor_id', '=', 'users.id')
				->whereIn('orders.distributor_id', $distributor_ids)
				->whereIn('orders.order_status', $this->order_statuses_for_points())
				->groupby('users.id')
				->orderBy('total_weight', 'desc')
				->limit(10)
				->get();

				

			$sales_reps = User::where('role', 'sales_rep')
				->where('assign_to_areamanager', $user->id)
				->pluck('id')->toArray();


			//show for top  distributors under him by points
			$distributor_point = User::select('users.*', DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
			WHEN operation = 'sub' then -points END) FROM points where user_id = users.id) as total_points"))
				->leftJoin('points', 'points.user_id', '=', 'users.id')
				->whereIn('points.user_id', $distributor_ids)
				->groupby('users.id')
				->orderBy('total_points', 'desc')
				->limit(10)
				->get();
				

			//show for top  sale rep under him by convertion
			$sale_convert = User::select('users.*', DB::raw('count(shops.created_by) As total'))
				->Join('shops', 'shops.created_by', '=', 'users.id')
				->where('sale_convert_status', 'final')
				->whereNotNull('sale_status_on')
				->whereIn('shops.created_by', $sales_reps)
				->orderBy('total', 'desc')
				->groupby('users.id')
				->limit(10)
				->get();

			//show for top  sale rep under him by points
			$sale_point = User::select('users.*', DB::raw('SUM(points.points) As points'))
				->leftJoin('points', 'points.user_id', '=', 'users.id')
				->whereIn('points.user_id', $sales_reps)
				->groupby('users.id')
				->orderBy('points', 'desc')
				->limit(10)
				->get();


			return view('admin.home.areamanager_index')->with(compact('user', 'distributor_volume', 'distributor_point', 'sale_convert', 'sale_point'));
		}
		return view('admin.home.index')->with(compact('user'));
	}
}
