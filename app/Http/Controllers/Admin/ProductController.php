<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\State;
use App\Models\ProductPrice;
use Illuminate\Support\Carbon;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;


class ProductController extends Controller
{
    public function index()
    {
        $specific_date = Carbon::now();
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

        $product_prices = ProductPrice::groupBy('start_date')->orderBy('start_date', 'asc')->get();

        $states_list = State::getStates();

        return view('admin.product.index')->with(compact('products', 'states_list', 'product_prices'));
    }

    public function state_by_point($state)
    {
        $state = urldecode($state);
        $states = State::getStates();

        if (!in_array($state, $states)) {
            abort(404);
        }

        $products = Product::where('is_disable', 0)->leftjoin('state_by_points as points', function ($join) use ($state) {
            $join->on('points.product_id', '=', 'products.id')
                ->where('points.state', $state);
        })
            ->select(['products.*', 'points.points_per_bundle_for_distributor as distributer_point', 'points.points_per_bundle_for_wholesaler as wholesaler_point'])->orderBy('sort', 'asc')->get();
        return view('admin.product.state_by_point', compact('products', 'state'));
    }

    public function point_store(Request $request)
    {

        $state = $request->get('state');

        foreach ($request->get('points') as $point) {
            DB::table('state_by_points')
                ->updateOrInsert(
                    ['product_id' => $point['id'], 'state' => $state],
                    [
                        'points_per_bundle_for_wholesaler' => $point['wholesaler_point'], 'points_per_bundle_for_distributor' => $point['distributor_point'], 'created_at' =>  \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]
                );
        }
        return back()->with('status', 'successfully update');
    }


    public function price_edit(Request $request){
        
        // $products = ProductPrice::where('start_date', $request->start_date)->get();
        $products = Product::where('is_disable', 0)
        ->leftJoin('product_prices', function ($join) use ($request) {
            $join->on('products.id', '=', 'product_prices.product_id')
                ->where('product_prices.start_date', '=', $request->start_date);
        })
        ->select(['products.*', 'product_prices.price', 'product_prices.id as price_id'])
        ->get();

        return view('admin.product.price_edit', compact('products'));
    }

    public function price_update(Request $request){

        foreach ($request->get('products') as $product) {

            ProductPrice::where('id', $product['price_id'])->update([
                'price' => $product['price'],
            ]);

        }

        return back()->with('status', 'successfully update');
    }

   // this function list the product according to filter  
    public function product_report(Request $request)
    {

        if (request()->ajax()) {
            $start_date = $request->input('start_date').':00:00:00';
            $end_date = $request->input('end_date').':23:59:00';
            $auth_user = auth()->user();
            $status = implode('\', \'', $this->order_statuses_for_points());
            $products =  Product::where('is_disable', 0)
                ->select('products.*');
                // filter on state
                if (!empty($request->input('state'))) {
                $state = $request->input('state');

                    if($auth_user->role == 'area_manager'){
                            // count no of boxes
                            $products =  $products->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date' AND orders.distributor_id IN (SELECT id FROM users WHERE `address_state`  = '$state' AND `assign_to_areamanager`  = $auth_user->id))) AS boxes"))
                            // calculate total order value of a product
                            ->addSelect(DB::raw("(SELECT SUM(line_price) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date'AND orders.distributor_id IN (SELECT id FROM users WHERE `address_state`  = '$state' AND `assign_to_areamanager`  = '$auth_user->id'))) AS total_value"))
                            // calculate total order volumn of a product
                            ->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date'AND orders.distributor_id IN (SELECT id FROM users WHERE `address_state`  = '$state' AND `assign_to_areamanager`  = '$auth_user->id'))) * products.weight_per_bundle / 1000 AS total_volumn"));

                    }else{
                            // count no of boxes
                            $products =  $products->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date' AND orders.distributor_id IN (SELECT id FROM users WHERE `address_state`  = '$state'))) AS boxes"))
                            // calculate total order value of a product
                            ->addSelect(DB::raw("(SELECT SUM(line_price) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date'AND orders.distributor_id IN (SELECT id FROM users WHERE `address_state`  = '$state'))) AS total_value"))
                            // calculate total order volumn of a product
                            ->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date'AND orders.distributor_id IN (SELECT id FROM users WHERE `address_state`  = '$state'))) * products.weight_per_bundle / 1000 AS total_volumn"));
                    }
                }else{
                
                    if($auth_user->role == 'area_manager'){
                        $products =  $products->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND `created_at` >= '$start_date' AND `created_at` <= '$end_date' AND orders.distributor_id IN (SELECT id FROM users WHERE `assign_to_areamanager` = '$auth_user->id'))) AS boxes"))

                        ->addSelect(DB::raw("(SELECT SUM(line_price) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date' AND orders.distributor_id IN (SELECT id FROM users WHERE `assign_to_areamanager` = '$auth_user->id'))) AS total_value"))
    
                        ->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date' AND orders.distributor_id IN (SELECT id FROM users WHERE `assign_to_areamanager` = '$auth_user->id'))) * products.weight_per_bundle / 1000 AS total_volumn"));
                    }else{
                        $products =  $products->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND `created_at` >= '$start_date' AND `created_at` <= '$end_date')) AS boxes"))

                        ->addSelect(DB::raw("(SELECT SUM(line_price) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date')) AS total_value"))
    
                        ->addSelect(DB::raw("(SELECT SUM(quantity) FROM order_lines WHERE products.id = order_lines.product_id AND order_lines.order_id IN (SELECT id FROM orders WHERE  `order_status` IN ('$status') AND `sub_stockist_id` IS NULL AND created_at >= '$start_date' AND created_at <= '$end_date')) * products.weight_per_bundle / 1000 AS total_volumn"));
                    }

                }
            // filter on sku series 
            if(!empty($request->input('sku_list'))){
                $barcodes = $request->input('sku_list');
                $products =  $products->where('barcodes', 'LIKE', "$barcodes%");
            }
               
            return DataTables::of($products)
            ->editColumn(
                'total_volumn',
                function ($row) {
                    return number_format(floatval($row->total_volumn), 3, '.', '') . ' Ton(s)';
                }
            )
            ->editColumn(
                'total_value',
                function ($row) {
                    return number_format(floatval($row->total_value), 3);
                }
            )
            ->editColumn(
                'boxes',
                function ($row) {
                    return number_format(floatval($row->boxes)) . ' Box(s)';                }
            )
            ->editColumn(
                'product_type',
                function ($row) {
                     if($row->pack_type == 'sachet'){
                       return ucfirst(str_replace('_', ' ', $row->product_type)) . ' '. $row->pcs_per_bundle . ' Sockets';
                     }else{
                         return ucfirst(str_replace('_', ' ', $row->product_type)) . ' '. $row->pack_size . ' ' . $row->pack_size_unit . ' ' . $row->pack_type;
                     }
                }
            )

            

            ->rawColumns(['total_volumn', 'total_value', 'boxes'])
                ->make(true);
        }
        $states = State::getStates();

        $sku_list = Product::sku_list();

        return view('admin.product.report', compact('states', 'sku_list'));
    }


    public function add_price()
    {

        if (!auth()->user()->can('admin_products')) {
            abort(403, 'Unauthorized action.');
        }


        $specific_date = Carbon::now();

        $products = Product::where('is_disable', 0)
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

        return view('admin.product.add_price', compact('products'));
    }

    public function store_price(Request $request){
        
        foreach ($request->get('products') as $product) {
            ProductPrice::create([
                'price' => $product['price'],
                'start_date' => $request->start_date,
                'product_id' => $product['id'],
            ]);
        }

        return back()->with('status', 'successfully Added');
    }
}
