<?php

namespace App\Http\Controllers\SalesRep;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\ShopVisit;
use Carbon\Carbon;

class ShopVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
   

        

        if (request()->ajax()) {
            $shopVisit = ShopVisit::where('sales_rep_id', $user->id)
                        ->join('shops as shops', 'shop_visits.shop_id', '=', 'shops.id')
                        ->leftjoin('users as sales_rep', 'shop_visits.sales_rep_id', '=', 'sales_rep.id')
                        ->leftjoin('users as dist', 'shops.assigned_distributor_id', '=', 'dist.id')
                        ->select(['shop_visits.*', 'sales_rep.name as sales_rep_name', 'shops.name as shops_name', 'shops.contact as shop_contact', 'shops.sale_convert_status', 'shops.id as shop_id', 'dist.company_name as company_name', 'dist.role as dist_role']);

            return DataTables::of($shopVisit)
                ->addColumn('action', function($row) use ($user){
                    if($row->sale_convert_status == null){
                      if($user->role == 'sales_rep'){
                        $html = '<a class="btn btn-sm btn-primary"
                        href="'.route("sales_rep.convert_sales", [$row->shop_id]).'">
                        Convert to sales
                    </a>';
                      }else{
                        $html = '<a class="btn btn-sm btn-primary"
                        href="'.route("sales_man.convert_sales", [$row->shop_id]).'">
                        Convert to sales
                    </a>';
                      }
                    } else {
                        if($row->sale_convert_status == 'pending_for_distributor'){
                            $html = '<span class="badge badge-warning">'.ucwords(str_replace('_', ' ', $row->sale_convert_status)).'</span>';
                        } elseif($row->sale_convert_status == 'pending_for_areamanager'){
                            $html = '<span class="badge badge-info">'.ucwords(str_replace('_', ' ', $row->sale_convert_status)).'</span>';
                        } elseif($row->sale_convert_status == 'pending_for_sub_stockist'){
                            $html = '<span class="badge badge-info">'.ucwords(str_replace('_', ' ', $row->sale_convert_status)).'</span>';
                        }
                        else{
                            $html = '<span class="badge badge-success">Converted</span>';
                        }   
                    }

                    return $html;
                })

                ->editColumn('company_name', function($row) use ($user){
                  if($row->company_name){
                    return '<span>' . $row->company_name . '</br>(' . $row->dist_role . ')</span>';
                  }
                })


                ->editColumn('created_at', function($row){
                    return Carbon::create($row->created_at)->toDayDateTimeString();
                })
                ->rawColumns(['action', 'company_name'])
                ->make(true);
        }

        return view('sales_rep.shop_visits.index')
            ->with(compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
