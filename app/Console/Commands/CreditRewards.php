<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\Reward;
use App\Notifications\CreditReward;



class CreditRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:rewards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cerdit note add to dist or super stockist';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $users = User::where('is_super_stockist', 1)->get();
        // foreach ($users as $user) {
        //     $total_reward = 0;
        //     $orders = Order::leftjoin('rewards', 'orders.id', '=', 'rewards.rewards_for_id')
        //         ->whereNull('rewards.id')
        //         ->where('orders.distributor_id', $user->id)
        //         ->whereIn('orders.order_status', ['order_dispatched', 'order_delivered'])
        //         ->whereNotNull('orders.sub_stockist_id')
        //         ->select('orders.*', 'orders.id as rewards_id')
        //         ->get();               
        //     foreach ($orders as $order) {
        //             $reward = (2 / 100) * $order->total_price;
        //             $total_reward = $total_reward + $reward;
        //             $reward_obj = new Reward(); 
        //             $reward_obj->reward_operation($order->distributor_id, $order->id, 'order_by_sub_stockist', $reward, 'add', $order->created_at);
        //     }

        //     if($total_reward > 0){
        //         $user->notify(new CreditReward($user, $total_reward));
        //     }
        // }
    }

    // $this->info($order->total_price);

}
