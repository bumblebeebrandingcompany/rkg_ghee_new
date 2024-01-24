<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Point;
use Illuminate\Support\Facades\Auth;
use App\Models\DistributorTarget;
use App\Models\ShopVisit;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_type = Request()->usertype;
        $user = Auth::user();
        $cy = $this->get_current_fy();
        $start = $cy['start'];
        $end = $cy['end'];


        if (request()->ajax()) {

            if ($user_type == 'sales_rep') {
                // $users = User::where('role', $user_type);
                if ($user->role == 'area_manager') {
                    $users = User::where('role', 'sales_rep')
                        ->where('assign_to_areamanager', $user->id)
                        ->select(['users.id', 'users.name', 'users.role', 'users.email', 'users.phone_no1', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no2', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        ->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                        ->addSelect(DB::raw("(SELECT COUNT(*) FROM shop_visits where sales_rep_id = users.id AND visited_at >= '$start' AND visited_at <= '$end') as shop_visited"))
                        ->addSelect(DB::raw("(SELECT COUNT(*) FROM shops where created_by = users.id AND sale_convert_status = 'final'  AND sale_status_on >= '$start' AND sale_status_on <= '$end') as shop_converted"));

                    //$users = User::whereIn('id', $sales_reps);
                } else {
                    $users = User::where('role', $user_type)
                        ->select(['users.id', 'users.name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.phone_no2', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        ->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                        ->addSelect(DB::raw("(SELECT COUNT(*) FROM shop_visits where sales_rep_id = users.id AND visited_at >= '$start' AND visited_at <= '$end') as shop_visited"))
                        ->addSelect(DB::raw("(SELECT COUNT(*) FROM shops where created_by = users.id AND sale_convert_status = 'final'  AND sale_status_on >= '$start' AND sale_status_on <= '$end') as shop_converted"));
                }
            } elseif ($user_type == 'admins') {
                $users = User::whereIn('role', $this->rkg_admin_roles(true));
            } elseif ($user_type == 'distributor') {
                if ($user->role == 'area_manager') {
                    $status = implode('\', \'', $this->order_statuses_for_points());
                    $users = User::where('users.role', 'distributor')
                        ->where('users.assign_to_areamanager', $user->id)
                        ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                        ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id')
                        ->select(['areamanager.name as area_manager', 'sales.name as sales_name', 'users.id', 'users.name', 'users.company_name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        ->addSelect(DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
                        WHEN operation = 'sub' then -points END) FROM points where user_id = users.id AND created_at >= $start AND created_at <= $end) as total_points"))
                        ->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets where distributor_id = users.id AND start_date >= $start AND end_date <= $end) as target_tonnage"))
                        ->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where distributor_id = users.id  AND `order_status` IN ('$status')AND created_at >= '$start' AND created_at <= '$end') as volume"));
                    //$users = User::whereIn('id', $distributor_ids);
                } else {



                    $status = implode('\', \'', $this->order_statuses_for_points());


                    $users = User::where('users.role', $user_type)
                        ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                        ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id')
                        ->select(['areamanager.name as area_manager', 'sales.name as sales_name', 'users.id', 'users.name', 'users.company_name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        // ->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = users.id AND operation = 'add' OR created_at >= $start AND created_at <= $end) as total_points"))
                        ->addSelect(DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
                        WHEN operation = 'sub' then -points END) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                        ->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets where distributor_id = users.id AND start_date >= '$start' AND end_date <= '$end') as target_tonnage"))
                        ->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where distributor_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start' AND created_at <= '$end') as volume"));
                }
            } elseif ($user_type == 'wholesaler') {
                if ($user->role == 'area_manager') {
                    $status = implode('\', \'', $this->order_statuses_for_points());
                    $users = User::where('users.role', $user_type)
                        ->where('users.assign_to_areamanager', $user->id)
                        ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                        ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id')
                        ->select(['areamanager.name as area_manager', 'sales.name as sales_name', 'users.id', 'users.name', 'users.company_name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        ->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                        ->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets where distributor_id = users.id AND start_date >= '$start' AND end_date <= '$end') as target_tonnage"))
                        ->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where distributor_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start' AND created_at <= '$end') as volume"));
                } else {
                    $status = implode('\', \'', $this->order_statuses_for_points());
                    $users = User::where('users.role', $user_type)
                        ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                        ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id')
                        ->select(['areamanager.name as area_manager', 'sales.name as sales_name', 'users.id', 'users.name', 'users.company_name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        ->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                        ->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets where distributor_id = users.id AND start_date >= '$start' AND end_date <= '$end') as target_tonnage"))
                        ->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where distributor_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start' AND created_at <= '$end') as volume"));
                }
            } elseif ($user_type == 'super_stockist') {
                $status = implode('\', \'', $this->order_statuses_for_points());
                $users = User::where('users.role', $user_type)->orWhere('users.is_super_stockist', 1)
                    ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                    ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id')
                    ->select(['areamanager.name as area_manager', 'sales.name as sales_name', 'users.id', 'users.name', 'users.company_name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                    ->addSelect(DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
                    WHEN operation = 'sub' then -points END) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                    ->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets where distributor_id = users.id AND start_date >= '$start' AND end_date <= '$end') as target_tonnage"))
                    ->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where distributor_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start' AND created_at <= '$end') as volume"));
            } elseif ($user_type == 'sub_stockist') {

                if ($user->role == 'area_manager') {
                    $status = implode('\', \'', $this->order_statuses_for_points());
                    $users = User::where('users.role', $user_type)->where('users.assign_to_areamanager', $user->id)
                        ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                        ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id')
                        ->select(['areamanager.name as area_manager', 'sales.name as sales_name', 'users.id', 'users.name', 'users.company_name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        ->addSelect(DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
                        WHEN operation = 'sub' then -points END) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                        ->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets where distributor_id = users.id AND start_date >= '$start' AND end_date <= '$end') as target_tonnage"))
                        ->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where sub_stockist_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start' AND created_at <= '$end') as volume"));
                } else {
                    $status = implode('\', \'', $this->order_statuses_for_points());
                    $users = User::where('users.role', $user_type)
                        ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                        ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id')
                        ->select(['areamanager.name as area_manager', 'sales.name as sales_name', 'users.id', 'users.name', 'users.company_name', 'users.email', 'users.role', 'users.assign_to_areamanager', 'users.assign_to_sales_rep', 'users.phone_no1', 'users.address_city', 'users.address_state', 'users.reference_id', 'users.distributor_discount', 'users.rewards_card_number', 'users.gst_number', 'users.pan_number'])
                        ->addSelect(DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
                        WHEN operation = 'sub' then -points END) FROM points where user_id = users.id AND created_at >= '$start' AND created_at <= '$end') as total_points"))
                        ->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets where distributor_id = users.id AND start_date >= '$start' AND end_date <= '$end') as target_tonnage"))
                        ->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where sub_stockist_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start' AND created_at <= '$end') as volume"));
                }
            }
            // filter by state
            if (!empty($request->input('state'))) {
                $users->where('users.address_state', $request->input('state'));
            }


            return DataTables::of($users, $user_type, $cy)
                ->editColumn('name', function ($row) {
                    $html = $row->name;
                    if (in_array($row->role, $this->rkg_admin_roles(true))) {
                        $html .= '<br/><i><small>' . $this->rkg_admin_roles()[$row->role] . '</small></i>';
                    }

                    return $html;
                })
                ->addColumn('action', function ($row) use ($user_type) {
                    if ($user_type == 'admins') {
                        $html = '<a class="btn btn-sm btn-primary"
                    href="' . action("App\Http\Controllers\Admin\UserController@edit", ['user' => $row->id, 'usertype' => 'admins']) . '">
                    Edit
                </a>';
                    } else {
                        $html = '<a class="btn btn-sm btn-primary"
                    href="' . action("App\Http\Controllers\Admin\UserController@edit", ['user' => $row->id, 'usertype' => $row->role]) . '">
                    Edit
                </a>';
                    }


                    if (auth()->user()->id != $row->id && auth()->user()->can('delete')) {
                        $html .= '<button class="btn btn-sm btn-danger delete_user"
                                data-href="' . action("App\Http\Controllers\Admin\UserController@destroy", ['user' => $row->id, 'usertype' => $user_type]) . '">
                                Delete
                            </button>';
                    }
                    return $html;
                })
                ->addColumn(
                    'address',
                    function ($row) {
                        return $row->address_line_1 . '<br/>' . $row->address_line_2 . '<br/>' . $row->address_city . ' ' . $row->address_state . ' ' . $row->address_zip;
                    }
                )
                ->editColumn(
                    'points',
                    function ($row) use ($cy) {
                        if ($row->role == 'distributor' || $row->role == 'wholesaler' || $row->role == 'sales_rep' || $row->role == 'sales_man' || $row->role == 'super_stockist' || $row->role == 'sub_stockist') {
                            return number_format($row->total_points);
                        }
                    }
                )

                ->editColumn(
                    'target_tonnage',
                    function ($row) use ($cy) {
                        if ($row->role == 'distributor' || $row->role == 'wholesaler' || $row->role == 'super_stockist' || $row->role == 'sub_stockist') {
                            return number_format(floatval($row->target_tonnage), 3, '.', '') . ' Ton(s)';
                        }
                    }
                )

                ->editColumn(
                    'volume',
                    function ($row) use ($cy) {
                        if ($row->role == 'distributor' || $row->role == 'wholesaler' || $row->role == 'super_stockist' || $row->role == 'sub_stockist') {
                            return number_format(floatval($row->volume) / 1000, 3, '.', '') . ' Ton(s)';
                        }
                    }
                )

                ->addColumn(
                    'achievement',
                    function ($row) {
                        if ($row->target_tonnage != 0 && ($row->role == 'distributor' || $row->role == 'wholesaler' || $row->role == 'super_stockist' || $row->role == 'sub_stockist')) {
                            return number_format(((floatval($row->volume) / 1000) / floatval($row->target_tonnage)) * 100, 2, '.', '');
                        }
                    }
                )


                // ->addColumn(
                //     'area_manager',
                //     function ($row) {
                //         if ($row->role == 'distributor' || $row->role == 'wholesaler') {
                //             return User::where('id', $row->assign_to_areamanager)->first()->name ?? '';
                //         }
                //     }
                // )
                ->addColumn(
                    'sales_rep',
                    function ($row) {
                        if ($row->role == 'distributor') {
                            return User::where('id', $row->assign_to_sales_rep)->first()->name ?? '';
                        }
                    }
                )

                ->rawColumns(['address', 'action', 'name', 'achievement', 'points', 'target_tonnage'])
                ->make(true);
        }
        $states_list = State::getStates();
        return view('admin.user.index')->with(compact('user_type', 'states_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $user_type = Request()->usertype;
        $states_list = State::getStates();
        $sales_reps = User::getSalesRep();

        $super_stockists = User::getSuperstockist();
        $getAreamanager = User::getAreamanager();
        $current_fy_date = $this->get_current_fy();
        $rkg_admin_roles = $this->rkg_admin_roles();
        $distributor_c_name = User::getDistributorswithcompanyname();

        return view('admin.user.create')->with(compact('user_type', 'states_list', 'sales_reps', 'current_fy_date', 'rkg_admin_roles', 'getAreamanager', 'distributor_c_name', 'super_stockists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_type = $request->get('user_type');

        $user = Auth::user();
        if ($user_type == 'sales_rep') {
            if ($user->role == 'area_manager') {
                $input = $request->only(['name', 'email', 'password', 'phone_no1', 'phone_no2']);
                $input['assign_to_areamanager'] = $user->id;
            } else {
                $input = $request->only(['name', 'email', 'password', 'phone_no1', 'phone_no2', 'assign_to_areamanager']);
            }
        } elseif ($user_type == 'distributor') {
            $input = $request->only(['name', 'email', 'password', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'assign_to_sales_rep', 'distributor_discount', 'rewards_card_number', 'assign_to_areamanager', 'assign_to_areamanager_2', 'is_super_stockist']);
            if ($request->post('is_super_stockist')) {
                $input['is_super_stockist'] = $request->post('is_super_stockist');
            } else {
                $input['is_super_stockist'] = 0;
            }
        } elseif ($user_type == 'admins') {
            $input = $request->only(['name', 'email', 'password', 'phone_no1', 'phone_no2']);
        } elseif ($user_type == 'wholesaler') {
            $input = $request->only(['name', 'email', 'password', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'distributor_discount', 'rewards_card_number', 'assign_to_areamanager', 'assign_to_areamanager_2']);
        } elseif ($user_type == 'super_stockist') {
            $input = $request->only(['name', 'email', 'password', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'distributor_discount', 'rewards_card_number']);
            $input['is_super_stockist'] = 1;
        } elseif ($user_type == 'sub_stockist') {
            $input = $request->only(['name', 'email', 'password', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'distributor_discount', 'rewards_card_number', 'assign_to_super_stockist', 'assign_to_areamanager', 'assign_to_areamanager_2', 'assign_to_sales_rep']);
        } else {
            die();
        }




        //encode password
        $input['password'] = Hash::make($input['password']);

        //assign role
        $input['role'] = ($user_type != 'admins') ? $user_type : $request->get('role');


        $user = new User;
        foreach ($input as $k => $v) {
            $user->$k = $v;
        }
        // check for reference_id
        if (empty($request->get('reference_id'))) {
            $user->reference_id = $this->generateReferenceNumberForUser($input['role']);
        } else {
            $user->reference_id = $request->get('reference_id');
        }
        $user->save();

        if ($user_type == 'admins' && $request->get('role') == 'area_manager') {
            if (!empty($request->get('dist'))) {
                // assign areamanager to distrubutors
                User::whereIn('id', $request->get('dist'))->update([
                    'assign_to_areamanager' => $user->id,
                ]);
            }
            if (!empty($request->get('sales'))) {
                //  assign areamanager to sales
                User::whereIn('id', $request->get('sales'))->update([
                    'assign_to_areamanager' => $user->id,
                ]);
            }
        }
        //save target tonnage
        if ($user_type == 'distributor' || $user_type == 'wholesaler' || $user_type == 'super_stockist' || $user_type == 'sub_stockist') {
            $target_tonnage = $request->get('target_tonnage');
            $current_fy_date = $this->get_current_fy();
            $target = new DistributorTarget;
            $target->distributor_id = $user->id;
            $target->start_date = $current_fy_date['start'];
            $target->end_date = $current_fy_date['end'];
            $target->target_tonnage = $target_tonnage;
            $target->save();
        }

        return redirect(route('admin.users.index') . '?usertype=' . $user_type)
            ->with('status', 'User created successfully with reference id ' . $user->reference_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $user_type = request()->get('usertype');
        $roles = in_array($user_type, ['admins']) ? $this->rkg_admin_roles(true) : [$user_type];

        $user = User::whereIn('role', $roles)
            ->findOrFail($id);

        $states_list = State::getStates();
        $sales_reps = User::getSalesRep();
        $getAreamanager = User::getAreamanager();
        $rkg_admin_roles = $this->rkg_admin_roles();

        $sales_and_dist = User::where('assign_to_areamanager', $id)->pluck('id')->toArray();
        $distributor_c_name = User::getDistributorswithcompanyname();
        $super_stockists = User::getSuperstockist();


        return view('admin.user.edit')
            ->with(compact('user', 'states_list', 'user_type', 'sales_reps', 'rkg_admin_roles', 'getAreamanager', 'sales_and_dist', 'distributor_c_name', 'super_stockists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_type = $request->input('user_type');


        if (in_array($user_type, ['admins'])) {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2', 'reference_id']);
        } elseif (in_array($user_type, ['sales_rep'])) {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2', 'reference_id', 'assign_to_areamanager']);
        } elseif (in_array($user_type, ['distributor'])) {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'assign_to_sales_rep', 'distributor_discount', 'rewards_card_number', 'assign_to_areamanager', 'assign_to_areamanager_2', 'reference_id']);
            if ($request->post('is_super_stockist')) {
                $input['is_super_stockist'] = $request->post('is_super_stockist');
            } else {
                $input['is_super_stockist'] = 0;
            }
        } elseif (in_array($user_type, ['wholesaler'])) {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'distributor_discount', 'rewards_card_number', 'assign_to_areamanager', 'assign_to_areamanager_2', 'reference_id']);
        } elseif ($user_type == 'super_stockist') {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'distributor_discount', 'rewards_card_number']);
        } elseif ($user_type == 'sub_stockist') {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city', 'address_state', 'address_zip', 'gst_number', 'pan_number', 'distributor_discount', 'rewards_card_number', 'assign_to_super_stockist', 'assign_to_areamanager', 'assign_to_areamanager_2', 'assign_to_sales_rep']);
        } else {
            die();
        }

        //encrypt password
        if (!empty($request->input('password'))) {
            $input['password'] = Hash::make($request->input('password'));
        }

        if (!empty($request->input('role'))) {
            $input['role'] = $request->input('role');
        }




        //TODO:Unique Email Check
        $user = User::findOrFail($id);

        $user->update($input);

        if ($user_type == 'admins' && $request->get('role') == 'area_manager') {
            // assign null to previous sales and distributors
            User::where('assign_to_areamanager', $id)->whereIn('role', ['distributor', 'sales_rep'])->update([
                'assign_to_areamanager' => null,
            ]);
            if (!empty($request->get('dist'))) {
                // assign to distributors
                User::whereIn('id', $request->get('dist'))->update([
                    'assign_to_areamanager' => $id,
                ]);
            }
            if (!empty($request->get('sales'))) {
                // assign to distributors
                User::whereIn('id', $request->get('sales'))->update([
                    'assign_to_areamanager' => $id,
                ]);
            }
        }

        return redirect(route('admin.users.index', ['usertype' => $user_type]))
            ->with('status', 'User updated successfully with reference id ' . $user->reference_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {

                $user_type = request()->get('usertype');
                $roles = in_array($user_type, ['admins']) ? ['admin', 'reports_only'] : [$user_type];

                $user = User::whereIn('role', $roles)
                    ->findOrFail($id);

                if (auth()->user()->id != $user->id) {
                    $user->delete();
                    $output = [
                        'success' => true,
                        'msg' => 'Success.'
                    ];
                } else {
                    $output = [
                        'success' => false,
                        'msg' => "Something went wrong."
                    ];
                }
            } catch (\Exception $e) {
                $output = [
                    'success' => false,
                    'msg' => 'Something went wrong.'
                ];
            }
            return $output;
        }
    }

    /**
     * Generates a unique reference number
     *
     * @param  string $user_type
     * @return string
     */
    public function generateReferenceNumberForUser($user_type)
    {

        if (in_array($user_type, $this->rkg_admin_roles(true))) {
            $num = User::whereIn('role', $this->rkg_admin_roles(true))->count() + 1001;
        } else {
            $num = User::where('role', $user_type)->count() + 1001;
        }

        $prefix = '';

        if ($user_type == 'distributor') {
            $prefix = 'DSTR';
        } elseif ($user_type == 'sales_rep') {
            $prefix = 'SR';
        } elseif ($user_type == 'area_manager') {
            $prefix = 'AM';
        } elseif ($user_type == 'wholesaler') {
            $prefix = 'WS';
        } elseif ($user_type == 'super_stockist') {
            $prefix = 'SPS';
        } elseif ($user_type == 'sub_stockist') {
            $prefix = 'SS';
        } elseif ($user_type == 'sales_man') {
            $prefix = 'SM';
        }

        return $prefix . $num;
    }

    /**
     * Display a listing of dist and sales under area manager.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dist_sales_under_am(Request $request)
    {
        $id = $request->id;
        if (empty($id)) {
            $disp = User::where('role', 'distributor')->get();
            $wholesaler = User::where('role', 'wholesaler')->get();
            $sales = User::where('role', 'sales_rep')->get();
            return ['disp' => $disp, 'sales' => $sales, 'wholesaler' => $wholesaler];
        } else {
            $disp = User::where('assign_to_areamanager', $id)->where('role', 'distributor')->get();
            $sales = User::where('assign_to_areamanager', $id)->where('role', 'sales_rep')->get();
            $wholesaler = User::where('assign_to_areamanager', $id)->where('role', 'wholesaler')->get();
            return ['disp' => $disp, 'sales' => $sales, 'wholesaler' => $wholesaler];
        }
    }

    // this function list the users report according to filter
    public function report(Request $request)
    {
    
        $cy = $this->get_current_fy();
        $start = $cy['start'];
        $end = $cy['end'];
        $role = Request()->role;
        $performing = Request()->performing;
        $order_by = Request()->order_by;

        if (request()->ajax()) {
            $start_date = $request->input('start_date').':00:00:00';
            $end_date = $request->input('end_date').':23:59:00';

            if (!empty($request->input('role')) && !empty($request->input('performing')) && !empty($request->input('order_by'))) {
                $users = User::where('users.role', $role)
                    ->leftjoin('users as areamanager', 'users.assign_to_areamanager', '=', 'areamanager.id')
                    ->leftjoin('users as sales', 'users.assign_to_sales_rep', '=', 'sales.id');

                $users =  $users->select('users.name', 'users.email', 'users.reference_id', 'users.company_name', 'users.address_city', 'users.address_state', 'areamanager.name as areamanager_name', 'sales.name as sales_name');

                $users =  $users->addSelect(DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
                WHEN operation = 'sub' then -points END) FROM points where user_id = users.id AND created_at >= '$start_date' AND created_at <= '$end_date') as rewards"));

                $users =  $users->addSelect(DB::raw("(SELECT target_tonnage FROM distributor_targets WHERE distributor_id = users.id AND start_date >= '$start_date' AND end_date <= '$end_date') as target_volume"));

                $status = implode('\', \'', $this->order_statuses_for_points());

                if ($request->input('role') == 'distributor' || $request->input('role') == 'wholesaler') {
                    $users =  $users->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where distributor_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start_date' AND created_at <= '$end_date') as total_weight"));
                } else {
                    $users =  $users->addSelect(DB::raw("(SELECT SUM(total_weight) FROM orders where sub_stockist_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start_date' AND created_at <= '$end_date') as total_weight"));
                }

                if ($request->input('role') == 'distributor' || $request->input('role') == 'wholesaler') {
                    $users =  $users->addSelect(DB::raw("
                IFNULL((SELECT SUM(total_weight) FROM orders WHERE distributor_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start_date' AND created_at <= '$end_date') / 1000 / 
                       (SELECT target_tonnage FROM distributor_targets WHERE distributor_id = users.id AND start_date >= '$start_date' AND end_date <= '$end_date') * 100, 0) as percentage_of_acheivement
            "));
                } else {
                    $users =  $users->addSelect(DB::raw("IFNULL((SELECT SUM(total_weight) FROM orders WHERE sub_stockist_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start_date' AND created_at <= '$end_date') / 1000 / 
                       (SELECT target_tonnage FROM distributor_targets WHERE distributor_id = users.id AND start_date >= '$start_date' AND end_date <= '$end_date') * 100, 0) as percentage_of_acheivement
            "));
                }

                $users =  $users->addSelect(DB::raw("(SELECT SUM(CASE WHEN operation = 'add' then points
                WHEN operation = 'sub' then -points END) FROM points where user_id = users.id AND created_at >= '$start_date' AND created_at <= '$end_date') as total_points"));

                $status = implode('\', \'', $this->order_statuses_for_points());
                if ($request->input('role') == 'distributor' || $request->input('role') == 'wholesaler') {
                    $users =  $users->addSelect(DB::raw("(SELECT SUM(total_price) FROM orders where distributor_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start_date' AND created_at <= '$end_date') as total_order_value"));
                } else {
                    $users =  $users->addSelect(DB::raw("(SELECT SUM(total_price) FROM orders where sub_stockist_id = users.id AND `order_status` IN ('$status') AND created_at >= '$start_date' AND created_at <= '$end_date') as total_order_value"));
                }

                if (!empty($request->input('state'))) {
                    $users->where('users.address_state', $request->input('state'));
                }

                if (!empty($request->input('sales_rep'))) {
                    $users->where('sales.id', $request->input('sales_rep'));
                }

                if (!empty($request->input('area_manager'))) {
                    $users->where('areamanager.id', $request->input('area_manager'));
                }



                return DataTables::of($users)
                    ->editColumn(
                        'total_points',
                        function ($users) {

                            return number_format($users->total_points);
                        }
                    )
                    ->editColumn(
                        'rewards',
                        function ($users) {

                            return number_format($users->rewards * 5);
                        }
                    )
                    ->editColumn(
                        'total_weight',
                        function ($users) {

                            return number_format(floatval(
                                $users->total_weight
                            ) / 1000, 3, '.', '') . ' Ton(s)';
                        }
                    )

                    ->editColumn(
                        'percentage_of_acheivement',
                        function ($users) {
                            return number_format($users->percentage_of_acheivement, 3);
                        }
                    )
                    ->editColumn(
                        'total_order_value',
                        function ($users) {
                            return number_format($users->total_order_value, 3);
                        }
                    )
                    ->editColumn(
                        'target_volume',
                        function ($row) use ($cy) {
                            return number_format(floatval($row->target_volume), 3, '.', '') . ' Ton(s)';
                        }
                    )

                    ->rawColumns(['total_points', 'total_weight', 'percentage_of_acheivement', 'total_order_value', 'rewards', 'target_volume'])
                    ->make(true);
            }
        }
        $states = State::getStates();
        $sales_rep = User::getSalesRep();
        $area_managers = User::getAreamanager();
        return view('admin.user.report', compact('role', 'performing', 'order_by', 'states', 'sales_rep', 'area_managers'));
    }
}
