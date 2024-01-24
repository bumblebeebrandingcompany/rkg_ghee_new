<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Point;
use App\Models\OrderLine;
use App\Models\Reward;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use App\Notifications\OrderStatusChanged;
use App\Notifications\PointsAdded;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;

use function PHPUnit\Framework\isNull;

class OrderController extends Controller
{


    public function __construct()
    {
        set_time_limit(60);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $ordertype = Request()->ordertype;


        if (isset($ordertype)) {
            if ($ordertype != 'super_stockist' && $ordertype != 'edit_date') {
                abort(404);
            }
        }

        if (request()->ajax()) {

            if (in_array($user->role, $this->rkg_admin_roles(true))) {
                //if admin show all orders
                if (isset($ordertype) && $ordertype == 'super_stockist') {
                    $orders = Order::whereNotNull('orders.created_at')->whereNotNull('sub_stockist_id');
                } else {
                    $orders = Order::whereNotNull('orders.created_at')->whereNull('sub_stockist_id');
                }
                if ($user->role == 'area_manager') {

                    //show for all distributors under him

                    if (isset($ordertype) && $ordertype == 'super_stockist') {
                        $sub_stockist_ids = User::whereIn('role', ['sub_stockist'])
                            ->where('assign_to_areamanager', $user->id)
                            ->pluck('id')->toArray();
                        $orders = Order::whereIn('sub_stockist_id', $sub_stockist_ids)->whereNotNull('sub_stockist_id');
                    } else {
                        $distributor_ids = User::whereIn('role', ['distributor', 'wholesaler'])
                            ->where('assign_to_areamanager', $user->id)
                            ->pluck('id')->toArray();
                        $orders->whereIn('distributor_id', $distributor_ids)->whereNull('sub_stockist_id');
                    }
                }
            } elseif ($user->role == 'sales_rep') {
                //if sales rep show related orders\
                $orders = Order::where('sales_rep_id', $user->id);
            } elseif ($user->role == 'wholesaler') {

                //if dist show there orders
                $orders = Order::where('distributor_id', $user->id);
            } elseif ($user->role == 'distributor') {
                // check order type  for distbutor
                if (isset($ordertype) && $ordertype == 'super_stockist') {
                    $orders = Order::where('distributor_id', $user->id)->whereNotNull('sub_stockist_id');
                } else {
                    $orders = Order::where('distributor_id', $user->id)->whereNull('sub_stockist_id');
                }
            } elseif ($user->role == 'sub_stockist') {
                $orders = Order::where('sub_stockist_id', $user->id);
            } elseif ($user->role == 'super_stockist') {

                if (isset($ordertype) && $ordertype == 'super_stockist') {
                    $orders = Order::where('distributor_id', $user->id)->whereNotNull('sub_stockist_id');
                } else {
                    $orders = Order::where('distributor_id', $user->id)->whereNull('sub_stockist_id');
                }
                // $orders = Order::where('distributor_id', $user->id);
            }
            $orders->join('users as distributor', 'orders.distributor_id', '=', 'distributor.id')
                ->leftjoin('users as sales_rep', 'distributor.assign_to_sales_rep', '=', 'sales_rep.id')
                // ->leftjoin('points as earn_point', function ($join) {
                //     $join->on('orders.id', '=', 'earn_point.points_for_id')
                //         ->where('earn_point.points_for', 'orders');
                // })
                ->select(['orders.*', 'distributor.company_name as distributor_name', 'distributor.role as role', 'sales_rep.name as sales_rep_name', 'distributor.address_city as distributor_city', 'distributor.address_state as distributor_state']);


            if ($user->role == 'super_stockist' || $user->role == 'distributor') {

                if (isset($ordertype) && $ordertype == 'super_stockist') {
                    $orders->addSelect(DB::raw("(SELECT SUM(points) FROM points where points_for_id = orders.id AND user_id = orders.sub_stockist_id OR  points_for = 'orders_by_sub_stockist' AND points_for = 'orders') as points_earned"));
                } else {
                    $orders->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = orders.distributor_id AND points_for_id = orders.id OR points_for = 'orders_by_sub_stockist' AND points_for = 'orders') as points_earned"));
                }
            } else if ($user->role == 'sub_stockist') {
                $orders->addSelect(DB::raw("(SELECT SUM(points) FROM points where points_for_id = orders.id AND user_id = orders.sub_stockist_id OR  points_for = 'orders_by_sub_stockist' AND points_for = 'orders') as points_earned"));
            } else {

                if (isset($ordertype) && $ordertype == 'super_stockist') {
                    $orders->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = orders.sub_stockist_id AND points_for_id = orders.id OR points_for = 'orders_by_sub_stockist' AND points_for = 'orders') as points_earned"));
                } else {
                    $orders->addSelect(DB::raw("(SELECT SUM(points) FROM points where user_id = orders.distributor_id AND points_for_id = orders.id OR points_for = 'orders_by_sub_stockist' AND points_for = 'orders') as points_earned"));
                }
            }
            $orders->addSelect(DB::raw("(SELECT SUM(rewards) FROM rewards where rewards_for_id = orders.id AND  rewards_for = 'orders' ) as rewards_use"));


            //filter by status
            if (!empty($request->input('status'))) {
                $orders->where('order_status', $request->input('status'));
            }

            //filter by sale rep
            if (!empty($request->input('sales_rep'))) {
                $distributor_ids = User::where('role', 'distributor')
                    ->where('assign_to_sales_rep', $request->input('sales_rep'))
                    ->pluck('id')->toArray();
                $orders->whereIn('distributor_id', $distributor_ids);
            }

            //filter by distributer
            if (!empty($request->input('distributor'))) {
                $orders->where('distributor_id', $request->input('distributor'))->whereNull('sub_stockist_id');
            }


            //filter by wholesaler
            if (!empty($request->input('wholesaler'))) {
                $orders->where('distributor_id', $request->input('wholesaler'));
            }

            //filter by area_manager
            if (!empty($request->input('area_manager'))) {
                $distributor_ids = User::whereIn('role', ['distributor', 'wholesaler'])
                    ->where('assign_to_areamanager', $request->input('area_manager'))
                    ->pluck('id')->toArray();
                $orders->whereIn('distributor_id', $distributor_ids);
            }

            // filter by sub stockisr
            if (!empty($request->input('sub_stockist'))) {
                $orders->where('sub_stockist_id', $request->input('sub_stockist'));
            }

            // filter by state
            if (!empty($request->input('state'))) {
                $orders->where('distributor.address_state', $request->input('state'));
            }


            //filter by created at
            if (!empty($request->input('start_date')) && !empty($request->input('end_date'))) {
                $orders->whereDate('orders.created_at', '>=', $request->input('start_date'))
                    ->whereDate('orders.created_at', '<=', $request->input('end_date'));
            }


            return DataTables::of($orders, $ordertype)


                ->addColumn('action', function ($row) use ($user, $ordertype) {
                    $html = '';

                    //distributor can only edit if order is draft

                    if (($user->role == 'distributor' || $user->role == 'wholesaler' || $user->role == 'sub_stockist' || $user->role == 'super_stockist') && ($row->order_status == "draft")) {
                        if ($user->role == 'wholesaler') {
                            $html .= '<a href="' . route("wholesaler.orders.edit", $row->id) . '" class="btn btn-primary btn-sm me-1">Edit</a>';
                        } elseif ($user->role == 'sub_stockist') {
                            $html .= '<a href="' . route("sub_stockist.orders.edit", $row->id) . '" class="btn btn-primary btn-sm me-1">Edit</a>';
                        } elseif ($user->role == 'super_stockist' && !isset($ordertype)) {
                            $html .= '<a href="' . route("super_stockist.orders.edit", $row->id) . '" class="btn btn-primary btn-sm me-1">Edit</a>';
                        } else {
                            if (!$ordertype) {
                                $html .= '<a href="' . route("dist.orders.edit", $row->id) . '" class="btn btn-primary btn-sm me-1">Edit</a>';
                            }
                        }
                    }

                    if ($row->order_status != 'order_cancelled' && $user->can('edit_order') && !isset($ordertype)) {
                        $html .= '<a href="' . route("admin.orders.edit", $row->id) . '" class="btn btn-primary btn-sm me-1">Edit</a>';
                    }

                    if (($user->role == 'super_stockist' || $user->role == 'distributor') && isset($ordertype) && $row->order_status != 'order_cancelled') {
                        $html .= '<a href="' . route("super_stockist.orders.edit", $row->id) . '?ordertype=super_stockist" class="btn btn-primary btn-sm me-1">Edit</a>';
                    }
                    if (($row->order_status == 'draft' && $user->can('delete_draft_order')) && $user->can('delete')) {
                        if ($user->role == 'distributor') {
                            if (is_null($row->sub_stockist_id)) {
                                $html .= '<button data-href="' . route("dist.orders.destroy", $row->id) . '" type="button" class="btn btn-danger btn-sm me-1 delete_order">Delete</button>';
                            }
                        } else {
                            if (is_null($row->sub_stockist_id) && $ordertype != 'edit_date') {
                                $html .= '<button data-href="' . route("admin.orders.destroy", $row->id) . '" type="button" class="btn btn-danger btn-sm me-1 delete_order">Delete</button>';
                            }
                        }
                    }



                    if (!in_array($user->role, $this->rkg_admin_roles(true))) {
                        if ($user->role == 'distributor') {
                            $html .= '<a href="' . route("dist.orders.show", $row->id) . '" class="btn btn-info btn-sm me-1">View</a>';
                        } elseif ($user->role == 'wholesaler') {
                            $html .= '<a href="' . route("wholesaler.orders.show", $row->id) . '" class="btn btn-success btn-sm me-1">View</a>';
                        } elseif ($user->role == 'sub_stockist') {
                            $html .= '<a href="' . route("sub_stockist.orders.show", $row->id) . '" class="btn btn-success btn-sm me-1">View</a>';
                        } elseif ($user->role == 'super_stockist') {
                            $html .= '<a href="' . route("super_stockist.orders.show", $row->id) . '" class="btn btn-info btn-sm me-1">View</a>';
                        }
                    } else {
                        if ($ordertype != 'edit_date') {
                            $html .= '<a href="' . route("admin.orders.show", $row->id) . '" class="btn btn-success btn-sm me-1">View </a>';
                        }
                    }


                    if (($row->order_status != 'order_cancelled') && !empty($row->invoice_file_name)) {
                        if ($ordertype != 'edit_date') {
                            $html .= '<button data-href="' . route("view_invoice", ['id' => $row->id]) . '" class="btn btn-primary btn-sm me-1 view_invoice">Invoice</button>';
                        }


                        // $html .= '<a href="'.route("download.invoice", ['id' => $row->id]).'" class="btn btn-primary btn-sm me-1" target="_blank">Invoice</a>';
                    }

                    if (($row->order_status != 'order_cancelled') && $user->can('update_order_status')) {

                        if (in_array($user->role, ['distributor', 'super_stockist']) && !is_null($row->sub_stockist_id)) {
                            $html .= '<a data-href="' . route("admin.orders.edit_status", $row->id) . '" class="btn btn-success btn-sm me-1 update_status_link">Update Status </a>';
                        } elseif (!in_array($user->role, ['distributor', 'sub_stockist', 'super_stockist'])) {
                            if (is_null($row->sub_stockist_id) && $ordertype != 'edit_date') {
                                $html .= '<a data-href="' . route("admin.orders.edit_status", $row->id) . '" class="btn btn-success btn-sm me-1 update_status_link">Update Status </a>';
                            }
                        }
                    }

                    if (($row->order_status != 'order_cancelled') && $user->can('cancel_order')) {
                        if (is_null($row->sub_stockist_id) && $ordertype != 'edit_date') {
                            $html .= '<button data-href="' . route("admin.orders.cancel", ['id' => $row->id]) . '" class="btn btn-danger btn-sm me-1 cancel_order">Cancel Order</button>';
                        }
                    }

                    if ($ordertype == 'edit_date') {
                        $html .= '<a data-href="' . route("admin.orders.edit_date", $row->id) . '" class="btn btn-success btn-sm me-1 update_date_link">Update date </a>';
                    }

                    return $html;
                })



                ->editColumn('order_status', function ($row) {
                    $html =  '<span class="' . $this->order_statuses(true)[$row->order_status]['class'] . '">' . $this->order_statuses(true)[$row->order_status]['label'] . '</span>';
                    if ($row->order_edited) {
                        $html .= '<br/><br/><small><i>Order Edited</i></small>';
                    }
                    return $html;
                })
                // ->editColumn('total_price_show', 'Rs. {{ @num_format($total_price) }}')

                ->editColumn(
                    'total_price',
                    function ($row) {
                        return 'Rs. ' . number_format($row->total_price);
                    }
                )

                ->editColumn(
                    'price_for_calculate',
                    function ($row) {
                        return floatval($row->total_price);
                    }
                )

                ->editColumn(
                    'status_for_calculate',
                    function ($row) {

                        return $row->order_status;
                    }
                )

                // ->addColumn('points_earned', function($row){
                //     return  number_format(Point::where('points_for_id', $row->id)
                //                     ->where('points_for', 'orders')
                //                     ->sum('points'), 2);
                // })
                ->editColumn('points_earned', function ($row) {
                    return number_format($row->points_earned, 2);
                })
                ->editColumn('total_points', function ($row) {
                    return floatval($row->points_earned);
                })
                ->editColumn('total_weight_for_calculate', function ($row) {
                    return floatval($row->total_weight / 1000);
                })

                ->editColumn('total_weight', function ($row) {
                    return number_format(floatval($row->total_weight) / 1000, 3) . ' Ton(s)';
                })



                ->editColumn('rewards_use', function ($row) {
                    return number_format($row->rewards_use, 2);
                })


                ->editColumn('distributor_name', function ($row) use ($user) {


                    if (is_null($row->sub_stockist_id)) {
                        $role = $row->role;
                        $name = User::find($row->distributor_id)->company_name;
                    } else {

                        $role = 'Sub Stockist';
                        $name = User::find($row->sub_stockist_id)->company_name;
                    }

                    return '<span>' . $name . '</br>(' . $role . ')</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->toFormattedDateString();
                })
                ->rawColumns(['order_status', 'action', 'distributor_name', 'total_price', 'total_weight_for_calculate', 'price_for_calculate'])
                ->make(true);
        }

        $order_statuses = $this->order_statuses(true);
        $distributors = User::getDistributorswithcompanyname();
        $sales_rep = User::getSalesRep();
        $area_manager = User::getAreamanager();
        $wholesaler = User::getWholesaler();
        $sub_stockists = User::getsubStockist();

        $states = State::getStates();

        if ($user->role == 'area_manager') {
            $sales_rep = User::getSalesRepUnderAmanager($user->id);
            $distributors = User::getDistributorsUnderAmanager($user->id);
            $wholesaler = User::getWholesalersUnderAmanager($user->id);
            $sub_stockists = User::getsubStockistsUnderAmanager($user->id);
        }



        return view('orders.index')
            ->with(compact('user', 'order_statuses', 'distributors', 'sales_rep', 'area_manager', 'wholesaler', 'states', 'ordertype', 'sub_stockists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $user = auth()->user();
        $specific_date = Carbon::now();

        if ($user->role == 'super_admin') {

            $products = Product::where('is_disable', 0)
            ->orderBy('sort', 'asc')
            ->select(['products.*', 'product_prices.price as latest_price'])
            ->leftJoin('product_prices', function ($join) use ($specific_date) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.start_date', '=', function ($query) use ($specific_date) {
                        $query->selectRaw('MAX(start_date)')
                            ->from('product_prices')
                            ->whereColumn('product_prices.product_id', 'products.id')
                            ->where('product_prices.start_date', '<=', $specific_date);
                    });
            })
            ->get();

        } elseif ($user->role == 'distributor' || $user->role == 'wholesaler' || $user->role == 'sub_stockist' || $user->role == 'super_stockist') {

            $products = Product::where('is_disable', 0)->leftjoin('state_by_points as points', function ($join) {
                $join->on('points.product_id', '=', 'products.id')
                    ->where('points.state', auth()->user()->address_state);
            })
            ->leftJoin('product_prices', function ($join) use ($specific_date) {
                $join->on('products.id', '=', 'product_prices.product_id')
                    ->where('product_prices.start_date', '=', function ($query) use ($specific_date) {
                        $query->selectRaw('MAX(start_date)')
                            ->from('product_prices')
                            ->whereColumn('product_prices.product_id', 'products.id')
                            ->where('product_prices.start_date', '<=', $specific_date);
                    });
            })
            ->select(['products.*', 'points.points_per_bundle_for_distributor as distributer_point', 'points.points_per_bundle_for_wholesaler as wholesaler_point', 'product_prices.price as latest_price'])->orderBy('sort', 'asc')->get();
        }

        $rewards =  Reward::available_rewards($user);

        return view('orders.create')->with(compact('products', 'rewards'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

            $distributor = Auth::user();
            $products = $request->products;

            $order = new Order;

            //order status is draft as default, if order_send is clicked it redirects to verification scren before sending.


            $order->order_status = 'draft';

            if (Auth::user()->role == 'sub_stockist') {
                $order->distributor_id = $distributor->assign_to_super_stockist;
                $order->sub_stockist_id = $distributor->id;
            } else {
                $order->distributor_id = $distributor->id;
                $order->sales_rep_id = $distributor->assign_to_sales_rep;
            }

            $order->distributor_notes = $request->distributor_notes;

            //order id
            $last_order = Order::orderby('id', 'desc')->first();
            $new_order_id = empty($last_order) ? 1001 : (int)$last_order->reference_id + 1;
            $order->reference_id = $new_order_id; //Unique reference number
            $order->save();

            //order lines
            $order_lines = [];
            $total_points = 0;
            $total_weight = 0;
            $subtotal_amount = 0;
            foreach ($products as $product_id => $quantity) {
                if (!empty($quantity) && $quantity > 0) {
                    //calculate points earned
                    $points_earned = $this->get_qty_points($product_id, $quantity, $distributor);
                    $total_points += $points_earned;

                    //Amount
                    $line_price = $this->get_price_for_product($product_id, $quantity, $order->created_at);
                    $subtotal_amount += $line_price;

                    $order_lines[] = new OrderLine([
                        'product_id' => $product_id, 'quantity' => $quantity, 'points_earned' => $points_earned,
                        'line_price' => $line_price
                    ]);

                    //calculate total weight
                    $total_weight += $this->get_weight_for_product($product_id, $quantity);
                }
            }



            $order->order_lines()->saveMany($order_lines);

            $order->total_points = $total_points;
            $order->total_weight = $total_weight;
            $order->subtotal_amount = $subtotal_amount;
            // assign discount 0 for order by sub stockist
            if (Auth::user()->role == 'sub_stockist') {
                $order->discount_percent = 0;
                $order->gst_price = $this->calc_gst_price($this->calc_percent($subtotal_amount, 0), 12.00);
                $order->total_price = $this->calc_percent($subtotal_amount, 0) + $order->gst_price;
            } else {
                $order->discount_percent = $distributor->distributor_discount;
                $order->gst_price = $this->calc_gst_price($this->calc_percent($subtotal_amount, $distributor->distributor_discount), 12.00);
                $order->total_price = $this->calc_percent($subtotal_amount, $distributor->distributor_discount) + $order->gst_price;
            }
            $order->gst_percent = 12.00;

            if ($request->post('rewards')) {
                $order->total_price = $order->total_price - floatval($request->post('rewards'));
                $order->used_credit_notes_amount = floatval($request->post('rewards'));
            }
            $order->save();
            DB::commit();

            // redirect to verification screen before sending.
            if ($request->order_status == 'order_placed') {
                if ($distributor->role == 'wholesaler') {
                    return redirect(route('wholesaler.order.verify', $order->id));
                } elseif ($distributor->role == 'distributor') {
                    return redirect(route('dist.order.verify', $order->id));
                } elseif ($distributor->role == 'sub_stockist') {
                    return redirect(route('sub_stockist.order.verify', $order->id));
                }
            }
            if ($distributor->role == 'distributor') {
                return redirect(route('dist.orders.index'))
                    ->with('status', 'Order saved successfully with reference id ' . $order->reference_id);
            } elseif ($distributor->role == 'wholesaler') {
                return redirect(route('wholesaler.orders.index'))
                    ->with('status', 'Order saved successfully with reference id ' . $order->reference_id);
            } elseif ($distributor->role == 'sub_stockist') {
                return redirect(route('sub_stockist.orders.index'))
                    ->with('status', 'Order saved successfully with reference id ' . $order->reference_id);
            } elseif ($distributor->role == 'super_stockist') {
                return redirect(route('super_stockist.orders.index'))
                    ->with('status', 'Order saved successfully with reference id ' . $order->reference_id);
            }
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $order = Order::whereNotNull('created_at');

        if ($user->role == 'distributor') {
            $order->where('distributor_id', $user->id);
        }

        $order = $order->where('id', $id)
            ->with(['order_lines', 'order_lines.product'])
            ->firstOrFail();

        $status = $this->order_statuses(true)[$order->order_status];
        $distributor = User::findOrFail($order->distributor_id);
        $sub_stockist = [];
        if (!empty($order->sub_stockist_id)) {
            $sub_stockist = User::findOrFail($order->sub_stockist_id);
        }



        return view('orders.show')
            ->with(compact('order', 'status', 'distributor', 'sub_stockist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $ordertype = Request()->ordertype;
        $order = Order::where('order_status', '!=', 'order_cancelled');

        if ($user->role == 'distributor' || $user->role == 'wholesaler' || $user->role == 'super_stockist') {

            if (isset($ordertype) && $ordertype == 'super_stockist') {
                $order->where('distributor_id', $user->id)
                    ->whereNotNull('sub_stockist_id');
            } else {
                $order->where('distributor_id', $user->id)
                    ->where('order_status', 'draft');
            }
        } elseif ($user->role == 'sub_stockist') {
            $order->where('sub_stockist_id', $user->id)
                ->where('order_status', 'draft');
        } else {
            if (!$user->can('edit_order')) {
                abort(403, 'Unauthorized action.');
            }
        }

        $order = $order->where('id', $id)->firstorfail();


        $distributor = User::findorfail($order->distributor_id);
        
        // latest price to create date
        $specific_date = Carbon::parse($order->created_at);

        $products = Product::where('is_disable', 0)
        ->leftJoin('state_by_points as points', function ($join) use ($distributor) {
            $join->on('points.product_id', '=', 'products.id')
                ->where('points.state', $distributor->address_state);
        })
        ->leftJoin('product_prices', function ($join) use ($specific_date) {
            $join->on('products.id', '=', 'product_prices.product_id')
                ->where('product_prices.start_date', '=', function ($query) use ($specific_date) {
                    $query->selectRaw('MAX(start_date)')
                        ->from('product_prices')
                        ->whereColumn('product_prices.product_id', 'products.id')
                        ->where('product_prices.start_date', '<=', $specific_date);
                });
        })
        ->select([
            'products.*',
            'points.points_per_bundle_for_distributor as distributer_point',
            'points.points_per_bundle_for_wholesaler as wholesaler_point',
            'product_prices.price as latest_price'
        ])
        ->orderBy('sort', 'asc')
        ->get();

        $order_lines_formatted = [];
        if (!empty($order)) {
            $order_lines = OrderLine::where('order_id', $order->id)->get();

            foreach ($order_lines as $key => $value) {
                $order_lines_formatted[$value->product_id] = $value;
            }
        }

        $user_type = $distributor->role;

        $rewards =  Reward::available_rewards($distributor);


        return view('orders.edit')
            ->with(compact('products', 'order', 'order_lines_formatted', 'user_type', 'rewards'));
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

        $ordertype = Request()->ordertype;

        DB::beginTransaction();

        try {

            $user = Auth::user();
            $products = $request->products;

            $order = Order::where('order_status', '!=', 'order_cancelled')
                ->where('id', $id);

            if (in_array($user->role, $this->rkg_admin_roles(true))) {
                if (!$user->can('edit_order')) {
                    abort(403, 'Unauthorized action.');
                }
            } elseif ($user->role == 'sales_rep') {
                //if sales rep show related orders
                $order->where('sales_rep_id', $user->id);
            } elseif ($user->role == 'distributor' || $user->role == 'super_stockist' || $user->role == 'wholesaler') {
                //if dist show there orders
                if (isset($ordertype) && $ordertype == 'super_stockist') {
                    $order->where('distributor_id', $user->id)
                        ->whereNotNull('sub_stockist_id');
                } else {
                    $order->where('distributor_id', $user->id)
                        ->where('order_status', 'draft');
                }
            } elseif ($user->role == 'sub_stockist') {
                $order->where('sub_stockist_id', $user->id)
                    ->where('order_status', 'draft');
            }

            $order = $order->firstOrFail();


            $distributor = User::findorfail($order->distributor_id);



            $order->distributor_notes = $request->distributor_notes;


            $order->order_status = 'draft';




            $order->update();

            //Add/update order lines
            $new_order_lines = [];
            $total_points = 0;
            $total_weight = 0;
            $subtotal_amount = 0;
            $updated_ids = [];




            foreach ($products as $product_id => $line) {

                if (!empty($line['quantity']) && $line['quantity'] > 0) {


                    //update lines
                    if (!empty($line['order_line_id'])) {

                        $updated_ids[] = $line['order_line_id'];
                        $order_line = OrderLine::find($line['order_line_id']);
                        $order_line->quantity = $line['quantity'];
                        $points_earned = $this->get_qty_points($product_id, $line['quantity'], $distributor);
                        $total_points += $points_earned;
                        $line_price = $this->get_price_for_product($product_id, $line['quantity'], $order->created_at);
                        $subtotal_amount += $line_price;

                        $order_line->line_price = $line_price;
                        $order_line->points_earned = $points_earned;
                        $order_line->update();
                    } else {

                        $points_earned = $this->get_qty_points($product_id, $line['quantity'], $distributor);
                        $total_points += $points_earned;

                        $line_price = $this->get_price_for_product($product_id, $line['quantity'], $order->created_at);
                        $subtotal_amount += $line_price;

                        //add lines.
                        $new_order_lines[] = new OrderLine(['product_id' => $product_id, 'quantity' => $line['quantity'], 'points_earned' => $points_earned, 'line_price' => $line_price]);
                    }

                    //calculate total weight
                    $total_weight += $this->get_weight_for_product($product_id, $line['quantity']);
                }
            }

            //delete lines
            if (!empty($updated_ids)) {
                OrderLine::where('order_id', $id)->whereNotIn('id', $updated_ids)->delete();
            }

            $order->order_lines()->saveMany($new_order_lines);


            $order->total_points = $total_points;
            $order->total_weight = $total_weight;
            $order->subtotal_amount = $subtotal_amount;
            // assign discount 0 for order by sub stockist
            if (empty($order->sub_stockist_id)) {
                $order->discount_percent = $distributor->distributor_discount;
                $order->gst_price = $this->calc_gst_price($this->calc_percent($subtotal_amount, $distributor->distributor_discount), 12.00);
                $order->total_price = $this->calc_percent($subtotal_amount, $distributor->distributor_discount) + $order->gst_price;
            } else {
                $order->discount_percent = 0;
                $order->gst_price = $this->calc_gst_price($this->calc_percent($subtotal_amount, 0), 12.00);
                $order->total_price = $this->calc_percent($subtotal_amount, 0) + $order->gst_price;
            }

            if ($request->post('rewards')) {
                $order->total_price = $order->total_price - floatval($request->post('rewards'));
                $order->used_credit_notes_amount = floatval($request->post('rewards'));
            }


            //if done by rkg admin add order_edited true
            if (in_array($user->role, $this->rkg_admin_roles(true))) {
                $order->order_edited = true;
                Point::where('points_for', 'orders')->where('points_for_id', $order->id)->delete();
            }

            $order->update();

            DB::commit();


            //redirect to verification screen before sending.
            if ($request->order_status == 'order_placed') {

                if (isset($ordertype) && $ordertype == 'super_stockist') {
                    return redirect(route($this->prefix_route('order.verify'), $order->id) . "?ordertype=super_stockist");
                } else {
                    return redirect(route($this->prefix_route('order.verify'), $order->id));
                }
            }

            return redirect(route($this->prefix_route('orders.index')))
                ->with('status', 'Order update successfully with reference id ' . $order->reference_id);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $user = Auth::user();

            $order = Order::where('id', $id);

            if ($user->role == 'distributor' || $user->role == 'wholesaler') {
                $order->where('distributor_id', $user->id);
            } elseif ($user->role == 'sub_stockist') {
                $order->where('sub_stockist_id', $user->id);
            }

            if (($order->first()->order_status == 'draft' && $user->can('delete_draft_order')) || $user->can('delete')) {


                $distributor = User::find($order->distributor_id);
                if ($distributor->role == 'distributor' &&  !is_null($order->sales_rep_id)) {
                    $this->__sub_distributor_points_to_salesrep($order->sales_rep_id);
                }

                $order->delete();
                OrderLine::where('order_id', $id)->delete();
                Point::where('points_for', 'orders')->where('points_for_id', $id)->delete();
                Point::where('points_for_id', $id)->where('points_for', 'orders_by_sub_stockist')->delete();
                Reward::where('rewards_for_id', $id)->delete();
            }

            $output = [
                'success' => true,
                'msg' => __("messages.success")
            ];
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        if ($request->ajax()) {
            return $output;
        } else {
            if ($user->role == 'distributor') {
                return redirect(route('dist.orders.index'));
            } elseif ($user->role == 'sub_stockist') {
                return redirect(route('sub_stockist.orders.index'));
            } else {
                return redirect(route('wholesaler.orders.index'));
            }
        }
    }

    /**
     * Update the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_status($id)
    {

        if (request()->ajax()) {
            $user = Auth::user();

            if (!$user->can('update_order_status')) {
                abort(403, 'Unauthorized action.');
            }

            $order = Order::with('distributor')->findorfail($id);
            $order_statuses = $this->order_statuses();

            return view('orders.update_status')
                ->with(compact('order', 'order_statuses'))->render();
        }
    }



    public function update_status(Request $request, $id)
    {

        $user = Auth::user();

        if (!$user->can('update_order_status')) {
            abort(403, 'Unauthorized action.');
        }

        $order = Order::findorfail($id);
        $order->invoice_no = $request->invoice_no;
        $order->order_status = $request->order_status;

        if (!empty($request->file('invoice_file'))) {
            $file_name = 'invoice-' . $order->reference_id . '.' . $request->file('invoice_file')->extension();
            $path = $request->file('invoice_file')->storeAs(
                'public/invoice',
                $file_name
            );

            $order->invoice_file_name = $path;
        }

        $order->update();

        //Send notification to distributor
        $distributor = User::find($order->distributor_id);


        $sales = User::find($distributor->assign_to_sales_rep);

        if (empty($order->sub_stockist_id)) {
            $areamanager = User::find($distributor->assign_to_areamanager);
            $areamanager_2 = User::find($distributor->assign_to_areamanager_2);
        } else {
            $sub_stockist = User::find($order->sub_stockist_id);
            $areamanager = User::find($sub_stockist->assign_to_areamanager);
            $areamanager_2 = User::find($sub_stockist->assign_to_areamanager_2);
        }


        $items = OrderLine::where('order_id', $id)
            ->leftjoin('products as product', 'order_lines.product_id', '=', 'product.id')
            ->select('product.*', 'order_lines.*')->get();
        $item_html = $this->table_view($items);

        $boxes = OrderLine::where('order_id', $id)->sum('quantity');

        $sales_rep = [];
        $area_manager_2 = [];
        $area_manager = [];
        if ($sales) {
            $sales_rep[] = $sales->email;
        }
        if ($areamanager_2) {
            $area_manager_2[] = $areamanager_2->email;
        }
        if ($areamanager) {
            $area_manager[] = $areamanager->email;
        }

        $cc_array = array_merge($sales_rep, $area_manager, $area_manager_2);

        if (!empty($order->sub_stockist_id)) {
            $sub_stockist = User::find($order->sub_stockist_id);
            $cc_array = array_merge([$sub_stockist->email], $area_manager, $area_manager_2);
        }


        if ($request->order_status == 'order_placed') {
            if (empty($order->sub_stockist_id)) {
                $distributor->notify(new OrderStatusChanged($cc_array, $order, $boxes, $distributor, $item_html));
            } else {
                $sub_stockist = User::find($order->sub_stockist_id);
                $cc_array = array_merge([$distributor->email], $area_manager, $area_manager_2);
                $sub_stockist->notify(new OrderStatusChanged($cc_array, $order, $boxes, $distributor, $item_html));
            }
        }

        if (in_array($request->order_status, $this->order_statuses_for_points())) {

            if (empty($order->sub_stockist_id)) {
                $this->__add_distributor_points($id);
            } else {
                $this->__add_point_to_super_sub_stockist($id);
            }
        }

        //add point for the sales rep.
        if ($distributor->role == 'distributor' &&  !is_null($order->sales_rep_id) && in_array($request->order_status, $this->order_statuses_for_points())) {
            $this->__add_distributor_points_to_salesrep($order->sales_rep_id);
        }

        return back()
            ->with('status', 'Status updated for order ' . $order->reference_id);
    }

     /**
     * edit the created date of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit_date($id)
    {
        if (request()->ajax()) {
            $user = Auth::user();

            if (!$user->role == 'super_admin') {
                abort(403, 'Unauthorized action.');
            }

            $order = Order::with('distributor')->findorfail($id);

            return view('orders.update_date')
                ->with(compact('order'))->render();
        }
    }
 /**
     * Update the created date of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_date(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->role == 'super_admin') {
            abort(403, 'Unauthorized action.');
        }

        $order = Order::findorfail($id);

        $order_date = $request->post('date');

        $order_lines = OrderLine::where('order_id', $order->id)->get();

        $subtotal_amount = 0;

        foreach ($order_lines as $line) {
            //update line
            $order_line = OrderLine::find($line->id);  
            $line_price = $this->get_price_for_product($line->product_id, $line->quantity, $order_date);
            $subtotal_amount += $line_price;
            $order_line->line_price = $line_price;
            $order_line->update();
        }

        $order->subtotal_amount = $subtotal_amount;
        $order->discount_percent = $order->discount_percent;
        $order->gst_price = $this->calc_gst_price($this->calc_percent($subtotal_amount, $order->discount_percent), 12.00);
        $order->total_price = $this->calc_percent($subtotal_amount, $order->discount_percent) + $order->gst_price;

        $order->created_at = $order_date;
        
        $order->update();
        Point::where('points_for_id', $id)->where('points_for', 'orders')->update([
            'created_at' => $order->created_at
        ]);
        return back()
            ->with('status', 'Date updated for order ' . $order->reference_id);
    }

    /**
     * sales rep gets 50 points on every 1000 points of its distributor
     * This function calculates this logic add the point to sales rep.
     * 
     * @param $sales_rep_id int
     *
     * @return void
     */
    public function __add_distributor_points_to_salesrep($sales_rep_id)
    {

        $sales_rep = User::find($sales_rep_id);

        $distributors_for_salesrep = Order::where('sales_rep_id', $sales_rep_id)
            ->whereIn('order_status', $this->order_statuses_for_points())
            ->pluck('distributor_id')->toArray();

        $total_points = Point::whereIn('user_id', $distributors_for_salesrep)->where('points_for', 'orders')->sum('points');

        $sales_rep_total_points = Point::where('user_id', $sales_rep_id)->where('points_for', 'distributor')->sum('points');
        // this logic for calculate 50 points after every 1000 points  
        $points_to_sales_rep = (floor(($total_points  - (($sales_rep_total_points / 50) * 1000)) / 1000)) * 50;
        // check if points greater than 0 then add points to sales_rep
        if ($points_to_sales_rep > 0) {
            $point_obj = new Point();
            $point_obj->add_point($sales_rep, 'distributor', $points_to_sales_rep, 'add', null, true);
        }
    }

    public function viewInvoice($id)
    {
        $order = Order::findorfail($id);

        return view('orders.view_invoice')
            ->with(compact('order'))->render();
    }

    public function downloadInvoice($id)
    {
        $order = Order::findorfail($id);

        return \Storage::download($order->invoice_file_name);
    }


    /**
     * Shows verification screen for orders before sending order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verify($id)
    {
        $user = Auth::user();

        $order = Order::where('order_status', 'draft');

        if ($user->role == 'distributor' || $user->role == 'wholesaler' || $user->role == 'super_stockist') {
            $order->where('distributor_id', $user->id);
        } elseif ($user->role == 'sub_stockist') {
            $order->where('sub_stockist_id', $user->id);
        } else {
            if (!$user->can('edit_order')) {
                abort(403, 'Unauthorized action.');
            }
        }
        $order = $order->where('id', $id)
            ->with(['order_lines', 'order_lines.product'])
            ->firstOrFail();

        return view('orders.verify')
            ->with(compact('order', 'user'));
    }

    /**
     * Updates orders to order_placed
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verifySend($id)
    {
        $user = Auth::user();

        $order = Order::where('id', $id);


        if ($user->role == 'distributor' || $user->role == 'wholesaler' || $user->role == 'super_stockist') {
            $order->where('distributor_id', $user->id);
        } elseif ($user->role == 'sub_stockist') {
            $order->where('sub_stockist_id', $user->id);
        } else {
            if (!$user->can('edit_order')) {
                abort(403, 'Unauthorized action.');
            }
        }
        $order = $order->firstOrFail();
        $order->order_status = 'order_placed';
     
        // removed this feature

        // if (!empty($order->used_credit_notes_amount) && Reward::where('rewards_for_id', $order->id)->where('rewards_for', 'orders')->count() == 0) {
        //     Reward::reward_operation($order->distributor_id, $order->id, 'orders', $order->used_credit_notes_amount, 'subtract', $order->created_at);
        // }
        $order->update();

        //Send new order email to distributor & CC to admin and areamanager and sales reps
        $distributor = User::find($order->distributor_id);
        $sales = User::find($distributor->assign_to_sales_rep);
        if (empty($order->sub_stockist_id)) {
            $areamanager = User::find($distributor->assign_to_areamanager);
            $areamanager_2 = User::find($distributor->assign_to_areamanager_2);
        } else {
            $sub_stockist = User::find($order->sub_stockist_id);
            $areamanager = User::find($sub_stockist->assign_to_areamanager);
            $areamanager_2 = User::find($sub_stockist->assign_to_areamanager_2);
        }



        $items = OrderLine::where('order_id', $id)
            ->leftjoin('products as product', 'order_lines.product_id', '=', 'product.id')
            ->select('product.*', 'order_lines.*')->get();

        $item_html = $this->table_view($items);

        $boxes = OrderLine::where('order_id', $id)->sum('quantity');

        $sales_rep = [];
        $area_manager_2 = [];
        $area_manager = [];
        if ($sales) {
            $sales_rep[] = $sales->email;
        }
        if ($areamanager_2) {
            $area_manager_2[] = $areamanager_2->email;
        }
        if ($areamanager) {
            $area_manager[] = $areamanager->email;
        }

        $cc_array = array_merge($sales_rep, $area_manager, $area_manager_2);

        if (!empty($order->sub_stockist_id)) {
            $sub_stockist = User::find($order->sub_stockist_id);
            $cc_array = array_merge([$sub_stockist->email], $area_manager, $area_manager_2);
        }

        if (empty($order->sub_stockist_id)) {
            $distributor->notify(new OrderStatusChanged($cc_array, $order, $boxes, $distributor, $item_html));
        } else {
            $sub_stockist = User::find($order->sub_stockist_id);
            $cc_array = array_merge([$distributor->email], $area_manager, $area_manager_2);
            $sub_stockist->notify(new OrderStatusChanged($cc_array, $order, $boxes, $distributor, $item_html));
        }

        $user_phone = '91' . $user->phone_no1;

        $areamanager = User::find($user->assign_to_areamanager);
        if ($areamanager) {
            $user_phone = $user_phone . ',91' . $areamanager->phone_no1;
        }

        if ($order->order_status == 'order_placed' && empty($order->sub_stockist_id)) {
            $this->order_status_sms($order, $distributor, $boxes, $user_phone);
        } else {
            $this->order_status_sms_for_sub_stockist_orders($order, $user, $boxes, $user_phone);
        }


        if (!empty($order->sub_stockist_id)) {
            return redirect(route($this->prefix_route('orders.index')) . "?ordertype=super_stockist")
                ->with('status', 'Order send successfully with reference id ' . $order->reference_id);
        }

        return redirect(route($this->prefix_route('orders.index')))
            ->with('status', 'Order send successfully with reference id ' . $order->reference_id);
    }

    /**
     * cancels an order from admin side.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelOrder(Request $request, $id)
    {
        if (request()->ajax()) {
            $user = Auth::user();

            if (!$user->can('cancel_order')) {
                abort(403, 'Unauthorized action.');
            }

            $order = Order::findorfail($id);
            $order->order_status = 'order_cancelled';
            $order->update();

            Point::where('points_for_id', $id)->where('points_for', 'orders')->delete();
            Point::where('points_for_id', $id)->where('points_for', 'orders_by_sub_stockist')->delete();
            Reward::where('rewards_for_id', $id)->delete();

            $distributor = User::find($order->distributor_id);
            if ($distributor->role == 'distributor' &&  !is_null($order->sales_rep_id)) {
                $this->__sub_distributor_points_to_salesrep($order->sales_rep_id);
            }

            $output = [
                'success' => true,
                'msg' => __("messages.success")
            ];
            return $output;
        }
    }
    /**
     * sales rep gets 50 points on every 1000 points of its distributor but on cancel order points get subtract
     * 
     * @param $sales_rep_id int
     *
     * @return void
     */
    public function __sub_distributor_points_to_salesrep($sales_rep_id)
    {

        $sales_rep = User::find($sales_rep_id);

        $distributors_for_salesrep = Order::where('sales_rep_id', $sales_rep_id)
            ->whereIn('order_status', $this->order_statuses_for_points())
            ->pluck('distributor_id')->toArray();

        $dist_points_to_sales = Point::whereIn('user_id', $distributors_for_salesrep)
            ->where('points_for', 'orders')
            ->sum('points');

        $sales_rep_total_points = Point::where('user_id', $sales_rep_id)
            ->where('points_for', 'distributor')
            ->sum('points');

        $actual_points_of_sales = floor($dist_points_to_sales / 1000) * 50;
        // check points greater than required points than subtract the points
        if ($actual_points_of_sales < $sales_rep_total_points) {
            $deduct_points = $actual_points_of_sales - $sales_rep_total_points;
            $point_obj = new Point();
            $point_obj->add_point($sales_rep, 'distributor', $deduct_points, 'add', null, false);
        }
    }

    public function __add_distributor_points($id)
    {
        if (Point::where('points_for_id', $id)->where('points_for', 'orders')->count() == 0) {
            $points =  OrderLine::where('order_id', $id)->sum('points_earned');
            $point = new Point;
            $point->points_for_id = $id;
            $point->user_id = Order::where('id', $id)->first()->distributor_id;
            $point->points_for = 'orders';
            $point->points = $points;
            $point->operation = 'add';
            $point->created_at = Order::where('id', $id)->first()->created_at;
            $point->save();

            User::where('id', Order::where('id', $id)->first()->distributor_id)->first()->notify(new PointsAdded($point));
        }
    }


    public function __add_point_to_super_sub_stockist($id)
    {
        $point = new Point();
        if (Point::where('points_for_id', $id)->where('points_for', 'orders_by_sub_stockist')->count() == 0) {
            // 80 % points to super stockist dudect
            $points =  80 / 100 * OrderLine::where('order_id', $id)->sum('points_earned');
            $point = new Point;
            $point->points_for_id = $id;
            $point->user_id = Order::where('id', $id)->first()->distributor_id;
            $point->points_for = 'orders_by_sub_stockist';
            $point->points = $points;
            $point->operation = 'sub';
            $point->created_at = Order::where('id', $id)->first()->created_at;
            $point->save();



            // 80 % points to sub stockist
            $points =  80 / 100 * OrderLine::where('order_id', $id)->sum('points_earned');
            $point = new Point;
            $point->points_for_id = $id;
            $point->user_id = Order::where('id', $id)->first()->sub_stockist_id;
            $point->points_for = 'orders_by_sub_stockist';
            $point->points = $points;
            $point->operation = 'add';
            $point->created_at = Order::where('id', $id)->first()->created_at;
            $point->save();

            User::find(Order::where('id', $id)->first()->sub_stockist_id)->notify(new PointsAdded($point));
        }
    }



    public function table_view($items)
    {
        $item_html =    '<html>
                        <head>
                        <style> 
                        .tdclass {
                          border: 1px solid black;
                          border-collapse: collapse;
                        }
                        </style>
                        </head>
                            <body>
                                <table class="tdclass">
                                    <tr>
                                        <th class="tdclass">Product</th>
                                        <th class="tdclass">SKU</th>
                                        <th class="tdclass">Order Quantity</th>
                                    </tr>';

        foreach ($items as $value) {
            $item_html = $item_html . '<tr>';
            if ($value->pack_type != 'sachet') {
                $item_html = $item_html . '<td class="tdclass">' . ucwords(str_replace('_', ' ', $value->product_type)) . '</br> ' . $value->pack_size . ' '
                    . ucfirst($value->pack_size_unit) . ' ' . ucfirst($value->pack_type) . '</td>
                <td class="tdclass">' . $value->barcodes . '</td>
                <td class="tdclass">' . $value->quantity . ' boxes</td>';
            } elseif ($value->pack_type == 'sachet') {
                $item_html = $item_html . '<td class="tdclass">' . ucwords(str_replace('_', ' ', $value->product_type)) . '</br> ' . $value->pcs_per_bundle . ' Sachets</td>
                <td class="tdclass">' . $value->barcodes . '</td>
                <td class="tdclass">' . $value->quantity . ' boxes</td>';
            }

            $item_html = $item_html . '<tr>';
        }

        $item_html = $item_html . '</table></body></html>';

        return $item_html;
    }
}
