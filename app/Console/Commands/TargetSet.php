<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DistributorTarget;
use App\Models\User;
use Carbon\Carbon;

class TargetSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Target:Set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Target set for distributor and wholesaler on 1st march with 15 % increment';

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

    // this function incresse the target 15% and update start and end time with current financial year
     public function handle()
    {
        $data = User::whereIn('role', ['distributor', 'wholesaler', 'sub_stockist'])->get();
        
        foreach($data as $value){
        
            $now = now();
            $current_target = DistributorTarget::where('distributor_id', $value->id)->orderBy('created_at', 'desc')->first()->target_tonnage;

            $increment = ($current_target * 15/100);
            $after_increment = $current_target + $increment;
            
            $target = new DistributorTarget;
            $target->distributor_id = $value->id;
            $target->start_date = $now->year . '-04-01';
            $target->end_date = $now->addYears(1)->year . '-03-31';
            $target->target_tonnage = $after_increment;
            $target->save();
        }
    }    

}
