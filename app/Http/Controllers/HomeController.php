<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        
        $role = auth()->user()->role;

        if($role == 'distributor'){
            return redirect()->route('dist.home');
        }

        if(in_array($role, $this->rkg_admin_roles(true))){
            return redirect()->route('admin.home');
        }

        if($role == 'sales_rep'){
            return redirect()->route('sales_rep.home');
        }

     

        if($role == 'wholesaler'){
            return redirect()->route('wholesaler.home');
        }
        if($role == 'sub_stockist'){
            return redirect()->route('sub_stockist.home');
        }
        if($role == 'super_stockist'){
            return redirect()->route('super_stockist.home');
        }
    }

    /**
     * check if email exist or not
     *
     * @return \Illuminate\Http\Response
     */
    public function checkIfEmailExist(Request $request)
    {
        $email = $request->input('email');

        $query = User::where('email', $email);

        if (!empty($request->input('user_id'))) {
            $user_id = $request->input('user_id');
            $query->where('id', '!=', $user_id);
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


     public function checkIfreference_idExist(Request $request)
    {
        $reference_id = $request->input('reference_id');

        $query = User::where('reference_id', $reference_id);

        if (!empty($request->input('user_id'))) {
            $user_id = $request->input('user_id');
            $query->where('id', '!=', $user_id);
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
}
