<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(request()->ajax()){
            $states = State::get();
            return DataTables::of($states)
                        ->addColumn('action', function ($row) {
                            $html = '<a href="'. route('admin.state_by_point', urlencode($row->name)).'" class="btn btn-primary btn-sm me-1">Edit point</a>';
                            return $html;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
        }
        $states = $this->states_list();
        $add_states = State::get()->pluck('name')->toArray();
        return view('admin.state.index', compact('states', 'add_states'));
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
        $state_key = $request->post('state_key');

        $name = $this->states_list()[$state_key];

        State::create([
            'key' => $state_key,
            'name' => $name,
        ]);

        return back()->with('status', 'Created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(State $state)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State  $state
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        //
    }
}
