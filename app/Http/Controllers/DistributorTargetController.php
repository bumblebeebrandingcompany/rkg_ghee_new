<?php

namespace App\Http\Controllers;

use App\Models\DistributorTarget;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DistributorTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $user = Auth::user();

        if($user->role != 'super_admin'){
            abort(403, 'Unauthorized action.');
        }

        $current_fy_date = $this->get_current_fy();

        $user = User::whereIn('role', ['distributor', 'wholesaler', 'sub_stockist', 'super_stockist'])
                ->join('distributor_targets as target', 'users.id', '=', 'target.distributor_id')
                ->whereDate('start_date', '>=', $current_fy_date['start'])
                ->whereDate('end_date', '<=', $current_fy_date['end'])
                ->select('users.role', 'users.company_name', 'users.reference_id', 'target.*')->get();

                return view('admin.user.edit_target')->with(compact('user'));
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
        $user = Auth::user();
        if($user->role != 'super_admin'){
            abort(403, 'Unauthorized action.');
        }

        DistributorTarget::where('id', $request->post('target_id'))->update([
            'target_tonnage'=> $request->post('value'),
        ]);
        
        return json_encode(['status' => true , 'msg' => 'Target edit successfull']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DistributorTarget  $distributorTarget
     * @return \Illuminate\Http\Response
     */
    public function show(DistributorTarget $distributorTarget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DistributorTarget  $distributorTarget
     * @return \Illuminate\Http\Response
     */
    public function edit(DistributorTarget $distributorTarget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DistributorTarget  $distributorTarget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DistributorTarget $distributorTarget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DistributorTarget  $distributorTarget
     * @return \Illuminate\Http\Response
     */
    public function destroy(DistributorTarget $distributorTarget)
    {
        //
    }
}
