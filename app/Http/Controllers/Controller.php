<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Point;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function states_list()
    {
        return [
            'AP' => 'Andhra Pradesh',
            'AR' => 'Arunachal Pradesh',
            'AS' => 'Assam',
            'BR' => 'Bihar',
            'CT' => 'Chhattisgarh',
            'GA' => 'Goa',
            'GJ' => 'Gujarat',
            'HR' => 'Haryana',
            'HP' => 'Himachal Pradesh',
            'JK' => 'Jammu and Kashmir',
            'JH' => 'Jharkhand',
            'KA' => 'Karnataka',
            'KL' => 'Kerala',
            'MP' => 'Madhya Pradesh',
            'MH' => 'Maharashtra',
            'MN' => 'Manipur',
            'ML' => 'Meghalaya',
            'MZ' => 'Mizoram',
            'NL' => 'Nagaland',
            'OR' => 'Odisha',
            'PB' => 'Punjab',
            'RJ' => 'Rajasthan',
            'SK' => 'Sikkim',
            'TN' => 'Tamil Nadu',
            'TG' => 'Telangana',
            'TR' => 'Tripura',
            'UP' => 'Uttar Pradesh',
            'UT' => 'Uttarakhand',
            'WB' => 'West Bengal',
            'AN' => 'Andaman and Nicobar Islands',
            'CH' => 'Chandigarh',
            'DN' => 'Dadra and Nagar Haveli',
            'DD' => 'Daman and Diu',
            'LD' => 'Lakshadweep',
            'DL' => 'National Capital Territory of Delhi',
            'PY' => 'Puducherry'
        ];
    }

    public function order_statuses($cancel = false)
    {
        return Order::order_statuses($cancel);
    }

    //Order status used for points calculation
    public function order_statuses_for_points()
    {
        return [
            'order_dispatched', 'order_delivered'
        ];
    }

    //Order status used for points calculation
    public function rkg_admin_roles($only_key = false)
    {
        return User::rkgAdminRoles($only_key);
    }



    public function get_qty_points($product_id, $qty, $distributor)
    {


        $points = DB::table('state_by_points')->where('product_id', $product_id)->where('state', $distributor->address_state)->first();


        if ($distributor->role == 'distributor' || $distributor->role == 'super_stockist') {
            return $points->points_per_bundle_for_distributor * $qty;
        } else {
            return $points->points_per_bundle_for_wholesaler * $qty;
        }
    }

    public function get_weight_for_product($product_id, $qty)
    {
        $prod = Product::findOrFail($product_id);
        return $prod->weight_per_bundle * $qty;
    }

    public function get_price_for_product($product_id, $qty, $date)
    {
        // get latest price on created date

        $specific_date = Carbon::parse($date);

        $product_price = DB::table('product_prices')
            ->where('start_date', '<=', $specific_date)
            ->where('product_id', $product_id)
            ->orderBy('start_date', 'desc')
            ->first();

        if (!$product_price) {
            // If no record is found, throw an exception or handle the error as needed
            throw new \Exception('No price found before created date.');
        }
        return $product_price->price * $qty;
    }

    /**
     * Returns the current financial year start & end date
     * @param  int  $previous (default 0)
     * 
     * @return array
     */
    public function get_current_fy($previous = 0)
    {
        $start_month = 4;
        $end_month = $start_month - 1;
        if ($start_month == 1) {
            $end_month = 12;
        }

        $start_year = date('Y');
        //if current month is less than start month change start year to last year
        if (date('n') < $start_month) {
            $start_year = $start_year - 1;
        }

        $end_year = date('Y');
        //if current month is greater than end month change end year to next year
        if (date('n') > $end_month) {
            $end_year = $start_year + 1;
        }
        $start_date = $start_year . '-' . str_pad($start_month, 2, 0, STR_PAD_LEFT) . '-01';
        $end_date = $end_year . '-' . str_pad($end_month, 2, 0, STR_PAD_LEFT) . '-01';
        $end_date = date('Y-m-t', strtotime($end_date));

        $output = [
            'start' => $start_date,
            'end' =>  $end_date
        ];

        //to calculate previous fy years
        if ($previous != 0) {
            $output = [
                'start' => Carbon::createFromDate($output['start'])->subYears($previous)->toDateString(),
                'end' => Carbon::createFromDate($output['end'])->subYears($previous)->toDateString()
            ];
        }

        return $output;
    }

    /**
     * Calculates the percentage
     * @param  float  $principal
     * @param  float  $percent
     * 
     * @return float
     */
    public function calc_percent($principal, $percent)
    {
        return $principal - ($principal * $percent / 100);
    }
    /**
     * Calculates the gst
     * @param  float  $subtotal
     * @param  float gst percent
     * @return float
     */
    public function calc_gst_price($total, $gst_percent)
    {
        return $total * $gst_percent / 100;
    }

    /**
     * Prefixes the route based on users role.
     * 
     * @param  string  $principal
     * @return string
     */
    public function prefix_route($route)
    {
        $user = Auth::user();
        if ($user->role == 'distributor') {
            return "dist." . $route;
        } elseif ($user->role == 'wholesaler') {
            return "wholesaler." . $route;
        } elseif ($user->role == 'sub_stockist') {
            return "sub_stockist." . $route;
        } elseif ($user->role == 'super_stockist') {
            return "super_stockist." . $route;
        } else {
            return "admin." . $route;
        }
    }

    public function order_status_sms($order, $user, $boxes, $user_phone)
    {

        $message = "Dealer Name - " . $user->company_name . ", City - " . $user->address_city . " has placed an order Order ID - " . $order->reference_id . " To No of Boxes - " . $boxes . " Total Amount - " . $order->total_price . " Thanks RKG Ghee";

        $ApiKey = env('ApiKey');

        $ClientId = env('ClientId');

        $SenderId = env('SenderId');

        $url = "http://api.nsite.in/api/v2/SendSMS?ApiKey=" . urlencode($ApiKey) . "&ClientId=" . urlencode($ClientId) . "&SenderId=" . urlencode($SenderId) . "&Message=" . urlencode($message) . "&Is_Unicode=false&Is_Flash=false&MobileNumbers=" . urlencode($user_phone) . "";

        $response = Curl::to($url)->get();

        // dd($response);                        
    }

    public function order_status_sms_for_sub_stockist_orders($order, $user, $boxes, $user_phone)
    {        
        $message = "Dealer Name - " . $user->company_name . ", City - " . $user->address_city . " has placed an order Order ID - " . $order->reference_id . " To No of Boxes - " . $boxes . " Thanks RKG Ghee";

        $ApiKey = env('ApiKey');

        $ClientId = env('ClientId');

        $SenderId = env('SenderId');

        $url = "http://api.nsite.in/api/v2/SendSMS?ApiKey=" . urlencode($ApiKey) . "&ClientId=" . urlencode($ClientId) . "&SenderId=" . urlencode($SenderId) . "&Message=" . urlencode($message) . "&Is_Unicode=false&Is_Flash=false&MobileNumbers=" . urlencode($user_phone) . "";

        $response = Curl::to($url)->get();

        // dd($response);                        
    }
}
