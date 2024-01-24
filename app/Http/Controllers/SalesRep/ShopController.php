<?php

namespace App\Http\Controllers\SalesRep;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\ShopVisit;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Point;
use App\Notifications\ShopStatusChanged;
use App\Notifications\PointsAdded;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
      
        
        if (request()->ajax()) {
            $shops = Shop::leftjoin('users as distributor', 'assigned_distributor_id', '=', 'distributor.id')
                ->leftjoin('users as sales_rep', 'created_by', '=', 'sales_rep.id')
                ->leftjoin('shop_visits as visit_date', 'shops.id', '=', 'visit_date.shop_id')
                ->select(['shops.*', 'distributor.name as distributor_name', 'distributor.role as distributor_role', 'sales_rep.name as sales_rep_name', 'sales_rep.role as sales_rep_role', 'visit_date.visited_at as visited_at']);

            if ($user->role == 'sales_rep') {
                $shops->where('created_by', $user->id)
                    ->where('sale_convert_status', 'final');
            } elseif ($user->role == 'area_manager') {
                //Get sales rep under him
                $sales_reps = User::whereIn('role', ['sales_rep'])
                    ->where('assign_to_areamanager', $user->id)
                    ->pluck('id')->toArray();
                $shops->whereIn('created_by', $sales_reps);
            } elseif ($user->role == 'distributor' || $user->role == 'sub_stockist') {
                $shops->where('assigned_distributor_id', $user->id);
            }

            if (!empty($request->input('status'))) {
                $shops->where('sale_convert_status', $request->input('status'));
            }

            return DataTables::of($shops)
                ->addColumn('action', function ($row) use ($user) {
                    if ($user->role == 'sales_rep' || $user->role == 'sales_man') {
                        if (empty($row->sale_convert_status)) {
                            if ($user->role == 'sales_rep') {
                                $html = '<a class="btn btn-sm btn-primary"
                            href="' . route("sales_rep.convert_sales", [$row->id]) . '">
                            Convert to sales
                        </a>';
                            } else {
                                $html = '<a class="btn btn-sm btn-primary"
                            href="' . route("sales_man.convert_sales", [$row->id]) . '">
                            Convert to sales
                        </a>';
                            }
                        } elseif ($row->sale_convert_status == 'pending_for_areamanager') {
                            $html = '<span class="badge badge-info">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        } elseif ($row->sale_convert_status == 'pending_for_distributor') {
                            $html = '<span class="badge badge-warning">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        } elseif ($row->sale_convert_status == 'decline_by_distributor') {
                            $html = '<span class="badge badge-danger">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                            if ($row->decline_reason == 'other') {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->reason_desc)) . '</p>';
                            } else {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->decline_reason)) . '</p>';
                            }
                        } else {
                            $html = '<span class="badge badge-success">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        }
                    } elseif ($user->role == 'distributor' || $user->role == 'sub_stockist') {
                        if ($row->sale_convert_status == 'pending_for_areamanager') {
                            $html = '<span class="badge badge-info">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        } elseif ($row->sale_convert_status == 'pending_for_distributor' || $row->sale_convert_status == 'pending_for_sub_stockist') {
                            $html = '<span class="badge badge-warning">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                            $html .= '<a class="btn btn-sm btn-primary m-1"
                                href="' . route(prefix_route("approve_sales"), [$row->id]) . '">
                                Accept
                                </a><a data-toggle="modal" onClick="putid(' . $row->id . ')" data-target="#exampleModalCenter" id="decline_btn" class="btn btn-sm btn-danger m-1">
                                    Decline
                                </a>';
                        } elseif ($row->sale_convert_status == 'decline_by_distributor') {
                            $html = '<span class="badge badge-danger">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                            if ($row->decline_reason == 'other') {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->reason_desc)) . '</p>';
                            } else {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->decline_reason)) . '</p>';
                            }
                        } else {
                            $html = '<span class="badge badge-success">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        }
                    } elseif ($user->role == 'area_manager') {
                        if ($row->sale_convert_status == 'pending_for_areamanager') {
                            $html = '<span class="badge badge-info">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                            $html .= '<a class="btn btn-sm btn-primary m-1"
                                    href="' . route("admin.approve_sales", [$row->id]) . '">
                                    Approve
                                </a>';
                        } elseif ($row->sale_convert_status == 'decline_by_distributor') {
                            $html = '<span class="badge badge-danger">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                            if ($row->decline_reason == 'other') {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->reason_desc)) . '</p>';
                            } else {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->decline_reason)) . '</p>';
                            }

                            $html .= '<a class="btn btn-sm btn-primary m-1"
                                    href="' . route("admin.approve_sales", [$row->id]) . '">
                                    Approve
                                </a>';
                        } elseif ($row->sale_convert_status == 'decline_by_sub_stockist') {
                            $html = '<span class="badge badge-danger">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                            if ($row->decline_reason == 'other') {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->reason_desc)) . '</p>';
                            } else {
                                $html .= '</br><p>' . ucwords(str_replace('_', ' ', $row->decline_reason)) . '</p>';
                            }
                        } elseif ($row->sale_convert_status == 'pending_for_distributor' || $row->sale_convert_status == 'pending_for_sub_stockist') {
                            $html = '<span class="badge badge-warning">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        } else {
                            $html = '<span class="badge badge-success">' . ucwords(str_replace('_', ' ', $row->sale_convert_status)) . '</span>';
                        }
                    }
                    return $html;
                })
                //  ->addColumn(
                //     'visited_at',
                //     function ($row) {
                //       return  date("d/m/Y", strtotime($row->visited_at));
                //     }
                // )
                ->editColumn('visited_at', function ($user) {
                    return [
                        'display' => date("d/m/Y", strtotime($user->visited_at)),
                        'visited_at' => $user->visited_at
                    ];
                })

                ->editColumn('distributor_name', function ($row) {
                    return '<span>' . $row->distributor_name . '</br>(' . str_replace('_', ' ', $row->distributor_role) . ')</span>';
                })
                ->editColumn('sales_rep_name', function ($row) {
                    return '<span>' . $row->sales_rep_name . '</br>(' . str_replace('_', ' ', $row->sales_rep_role) . ')</span>';
                })



                ->filterColumn('visited_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(visited_at,'%m/%d/%Y') LIKE ?", ["%$keyword%"]);
                })
                ->rawColumns(['action', 'distributor_name', 'sales_rep_name'])
                ->make(true);
        }
     
        return view('sales_rep.shop.index')
            ->with(compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if ($user->role != 'sales_rep') {
            abort(403, 'Unauthorized action.');
        }

        return view('sales_rep.shop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = auth()->user();

        if ($user->role != 'sales_rep') {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {

            $shop = new Shop;
            $shop->name = $request->get('name');
            $shop->contact = $request->get('contact');
            $shop->location = $request->get('location');
            $shop->pin_code = $request->get('pin_code');
            $shop->gst_registered = $request->get('gst_registered');
            $shop->gst_number = $request->get('gst_number');
            //$shop->pan = $request->get('pan');
            $shop->existing_ghee_products = $request->get('existing_ghee_products');
            $shop->type_of_client = $request->get('type_of_client');
            // $shop->visited_at = $request->get('visited_at');
            $shop_count = shop::count() + 1;
            $shop->reference_id = 'SH/' . $shop_count; //Unique reference number

            $shop->created_by = $user->id;
            $shop->save();

            //create visit
            $visit = [];
            $path = '';

            if (!empty($request->file('visit_proof_selfie'))) {
                $file_name = 'proof-' . time() . '.' . $request->file('visit_proof_selfie')->extension();
                $path = $request->file('visit_proof_selfie')->storeAs(
                    'public/proof_selfie',
                    $file_name
                );
            }

            $visit[] = new ShopVisit([
                'sales_rep_id' => $user->id, 'visit_proof_selfie' => $path,
                'visited_at' => $request->get('visited_at')
            ]);
            $shop->visits()->saveMany($visit);

            $this->__add_visit_reward_points($user);

            DB::commit();


            return redirect(route(prefix_route('shop-visits.index')))
                ->with('status', 'Shop saved successfully with reference id ' . $shop->reference_id);
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

    /**
     * check if shop exist or not
     *
     * @return \Illuminate\Http\Response
     */
    public function checkIfShopExist(Request $request)
    {
        $contact = $request->input('contact');

        $query = Shop::whereNotNull('created_by');

        if (!empty($request->input('contact'))) {
            $query->where('contact', '=', $request->input('contact'));
        }

        if (!empty($request->input('gst_number'))) {
            $query->where('gst_number', '=', $request->input('gst_number'));
        }

        if (!empty($request->input('shop_id'))) {
            $shop_id = $request->input('shop_id');
            $query->where('id', '!=', $shop_id);
        }

        $exists = $query->exists();
        if (!$exists) {
            echo "true";
            exit;
        } else {
            echo "false";
            exit;
        }
    }

    /**
     * Convert sales
     *
     * @return \Illuminate\Http\Response
     */
    public function convertSales($id)
    {
        $user = auth()->user();

        if ($user->role != 'sales_rep' && $user->role != 'sales_man') {
            abort(403, 'Unauthorized action.');
        }

        $shop = Shop::findorfail($id);
        if ($shop->created_by != $user->id || !empty($shop->sale_converted_at)) {
            abort(403, 'Unauthorized action.');
        }

        //sales rep details
        $sales_rep = User::find($shop->created_by);

        //distribuutor list
        $distributors = User::where('role', 'distributor')->where('assign_to_sales_rep', $sales_rep->id)
            ->orderBy('company_name', 'asc')
            ->get();

        $sub_stockist = User::where('role', 'sub_stockist')->where('assign_to_sales_rep', $sales_rep->id)
            ->orderBy('company_name', 'asc')
            ->get();

        // display dist and sub in one array    

        $distributors =   $distributors->merge($sub_stockist);



        return view('sales_rep.shop.convert_sales')
            ->with(compact('shop', 'distributors', 'sales_rep'));
    }

    /**
     * Store Convert sales
     *
     * @return \Illuminate\Http\Response
     */
    public function storeConvertSales(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role != 'sales_rep') {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            $shop = Shop::findorfail($id);
            if ($shop->created_by != $user->id) {
                abort(403, 'Unauthorized action.');
            }




            $shop->sale_status_on = now();
            $shop->gst_number = $request->get('gst_number');
            // $shop->pan_number = $request->get('pan_number');
            $shop->assigned_distributor_id = $request->get('assigned_distributor_id');


            if (User::find($shop->assigned_distributor_id)->role == 'distributor') {
                $shop->sale_convert_status = 'pending_for_distributor';
            } else {
                $shop->sale_convert_status = 'pending_for_sub_stockist';
            }


            $gst_certificate_path = '';
            if (!empty($request->file('gst_certificate'))) {
                $file_name = 'gst-' . $shop->id . '-' . time() . '.' . $request->file('gst_certificate')->extension();
                $gst_certificate_path = $request->file('gst_certificate')->storeAs(
                    'public/shop_uploads',
                    $file_name
                );
            }
            $shop->gst_certificate = $gst_certificate_path;

            // $pan_certificate_path = '';
            // if(!empty($request->file('pan_certificate'))){
            //     $file_name = 'pan-' . $shop->id . '-' . time() . '.' . $request->file('pan_certificate')->extension();
            //     $pan_certificate_path = $request->file('pan_certificate')->storeAs(
            //                     'public/shop_uploads', $file_name
            //                 );
            // }
            //$shop->pan_certificate = $pan_certificate_path;

            $shop->update();


            //Send notification to distributor
            $distributor = User::find($shop->assigned_distributor_id);
            $distributor->notify(new ShopStatusChanged($shop, $distributor));

            //$this->__add_converted_reward_points($user);

            DB::commit();

            return redirect(route(prefix_route('shop-visits.index')))
                ->with('status', 'Success');
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
     * Approve sales by area manager
     *
     * @return \Illuminate\Http\Response
     */
    public function approve_sales($id)
    {
        $user = auth()->user();

        if ($user->role != 'area_manager') {
            abort(403, 'Unauthorized action.');
        }

        $shop = Shop::findorfail($id);
        if ($shop->sale_convert_status == 'pending_for_distributor' || $shop->sale_convert_status == 'pending_for_sub_stockist' || $shop->sale_convert_status == 'final') {
            abort(403, 'Unauthorized action.');
        }

        //sales rep details
        $sales_rep = User::find($shop->created_by);

        //distributor list
        $distributors = User::where('role', 'distributor')->where('assign_to_areamanager', $user->id)
            ->where('assign_to_areamanager', $user->id)
            ->orderBy('company_name', 'asc')
            ->get();

        // $sub_stockist = User::where('role', 'sub_stockist')->where('assign_to_areamanager', $user->id)
        //     ->orderBy('name', 'asc')
        //     ->get();

        // $distributors =   $distributors->merge($sub_stockist);



        return view('sales_rep.shop.approve_sales')
            ->with(compact('shop', 'distributors', 'sales_rep'));
    }

    /**
     * Store Approve sales
     *
     * @return \Illuminate\Http\Response
     */
    public function storeApproveSales(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->role != 'area_manager') {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            $shop = Shop::findorfail($id);

            if ($shop->sale_convert_status == 'pending_for_distributor' || $shop->sale_convert_status == 'pending_for_sub_stockist' || $shop->sale_convert_status == 'final') {
                abort(403, 'Unauthorized action.');
            }

            $shop->sale_convert_status = 'final';
            // $shop->sale_status_on = now();
            $shop->gst_number = $request->get('gst_number');
            //$shop->pan_number = $request->get('pan_number');
            $shop->assigned_distributor_id = $request->get('assigned_distributor_id');

            $shop->update();

            $sales_rep = User::find($shop->created_by);
            $this->__add_converted_reward_points($sales_rep, $shop->id);
            DB::commit();

            return redirect(route('admin.shops.index'))
                ->with('status', 'Success');
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
     * Calculates all the conditions for reward points and add them.
     * 
     * @param User object $sales_rep
     * @return boolean
     */
    private function __add_visit_reward_points($sales_rep)
    {
        //60 shop visits  + 2 shop conversions per month to claim the 150 reward points

        $visit_count = ShopVisit::thisMonthVisits($sales_rep->id);

        if ($visit_count == 60) {
            $point = new Point();
            $point->add_point($sales_rep, 'visits', 150, 'add', null, true);
            return true;
        }

        return false;
    }

    private function __add_converted_reward_points($sales_rep, $id)
    {
        //60 shop visits  + 2 shop conversions per month to claim the 150 reward points

        $convert_count = Shop::thisMonthConversion($sales_rep->id);

        if ($convert_count >= 2) {
            //200 points for first 2, then 100 each
            $points = ($convert_count == 2) ? 200 : 100;
            $point = new Point();
            $point->add_point($sales_rep, 'converted', $points, 'add', $id, true);
            return true;
        }
        return false;
    }
}
