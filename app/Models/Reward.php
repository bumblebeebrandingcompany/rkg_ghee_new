<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    public function reward_operation($user_id, $rewards_for_id, $rewards_for, $rewards, $operation, $created_at)
    {

        $reward = new Reward;
        $reward->user_id = $user_id;
        $reward->rewards_for = $rewards_for;
        $reward->rewards_for_id = $rewards_for_id;
        $reward->rewards = $rewards;
        $reward->operation = $operation;
        $reward->created_at = $created_at;
        $reward->save();
    }

    public static function available_rewards($user)
    {

        return Reward::where('user_id', $user->id)->where('operation', 'add')->sum('rewards') - Reward::where('user_id', $user->id)->where('operation', 'subtract')->sum('rewards');
    }
}
