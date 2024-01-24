<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $states_list = State::getStates();
        return view('user.profile.edit')
            ->with(compact('user', 'states_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!in_array(auth()->user()->role, ['distributor'])) {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2']);
        } elseif(in_array(auth()->user()->role, ['distributor'])) {
            $input = $request->only(['name', 'email', 'phone_no1', 'phone_no2', 'company_name', 'address_line_1', 'address_line_2', 'address_city','address_state', 'address_zip']);
        }
        
        //encrypt password
        if (!empty($request->input('password'))) {
            $input['password'] = \Hash::make($request->input('password'));
        }

        //TODO:Unique Email Check
        $user = User::findOrFail(auth()->user()->id);
        $user->update($input);

        return back()
            ->with('status', 'Profile updated.');
    }
}
