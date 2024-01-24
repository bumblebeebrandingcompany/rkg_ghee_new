<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getAllRoles()
    {
        $roles = [
            'admin' => 'Admin',
            'sales_rep' => 'Sales Representative',
            'distributor' => 'Distributor',
            'reports_only' => 'Reports Only'
        ];

        return $roles;
    }

    public static function rkgAdminRoles($only_key = false)
    {
        $roles =  [
            'super_admin' => 'Super Admin Access',
            'admin' => 'Admin',
            'area_manager' => 'Area Manager (RKG Family Member)',
            'order_manager' => 'Order Manager access',
            'order_superviser' => 'Order Superviser Access',

            // 'all_reports_only' => 'Reports only (All Reports)',
            // 'account_reports_only' => 'Reports only (Accounts related reports)',
            // 'view_only_access' => 'View Only Access'
        ];

        if ($only_key) {
            return array_keys($roles);
        } else {
            return $roles;
        }
    }

    public static function getSalesRep()
    {
        $users = User::where('role', 'sales_rep')
            ->pluck('name', 'id')
            ->toArray();
        return $users;
    }

    public static function getSuperstockist()
    {
        $users = User::where('is_super_stockist', 1)
            ->pluck('name', 'id')
            ->toArray();
        return $users;
    }

    public static function getsubStockist()
    {
      if(auth()->user()->role == 'distributor' || auth()->user()->role == 'super_stockist'){
        $users = User::where('role', 'sub_stockist')->where('assign_to_super_stockist', auth()->user()->id)
            ->pluck('company_name', 'id')
            ->toArray();
      }else{
        $users = User::where('role', 'sub_stockist')
        ->pluck('company_name', 'id')
        ->toArray();
      }
      
        return $users;
    }


    public static function getSalesRepUnderAmanager($id)
    {
        $sales_reps = User::where('role', 'sales_rep')
            ->where('assign_to_areamanager', $id)
            ->pluck('company_name', 'id')->toArray();
        return $sales_reps;
    }

    public static function getDistributorsUnderAmanager($id)
    {
        $distributors = User::where('role', 'distributor')
            ->where('assign_to_areamanager', $id)
            ->pluck('company_name', 'id')->toArray();
        return $distributors;
    }

    public static function getsubStockistsUnderAmanager($id)
    {
        $sub_stockists = User::where('role', 'sub_stockist')
            ->where('assign_to_areamanager', $id)
            ->pluck('company_name', 'id')->toArray();
        return $sub_stockists;
    }



    public static function getDistributorswithcompanyname()
    {
        $users = User::where('role', 'distributor')
            ->pluck('company_name', 'id')
            ->toArray();
        return $users;
    }

    public static function getAreamanager()
    {
        $users = User::where('role', 'area_manager')
            ->pluck('name', 'id')
            ->toArray();
        return $users;
    }

    public static function getWholesaler()
    {
        $users = User::where('role', 'wholesaler')
            ->pluck('company_name', 'id')
            ->toArray();
        return $users;
    }

    public static function getWholesalersUnderAmanager($id)
    {
        $users = User::where('role', 'wholesaler')
            ->where('assign_to_areamanager', $id)
            ->pluck('company_name', 'id')->toArray();
        return $users;
    }
}
