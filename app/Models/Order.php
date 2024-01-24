<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Get the Orderlines for a order
     */
    public function order_lines()
    {
        return $this->hasMany(OrderLine::class);
    }

    /**
     * Get the distributor
     */
    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }

    /**
     * Get the sales_rep
     */
    public function sales_rep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    /**
     * gives a list of order status
     *
     * @return array
     */
    public static function order_statuses($cancel = false)
    {
        $status = [
            'draft' => ['label' => __("messages.order_draft"), 'class' => 'badge badge-light'],
            'order_placed' => ['label' => __("messages.order_placed"), 'class' => 'badge badge-primary'],
            'order_invoiced' => ['label' => __("messages.order_invoiced"), 'class' => 'badge badge-warning'],
            'order_dispatched' => ['label' => __("messages.order_dispatched"), 'class' => 'badge badge-success'],
            'order_delivered' =>  ['label' => __("messages.order_delivered"), 'class' => 'badge badge-info'],
            // 'pending_for_super_stockist' => ['label' => __("messages.pending_for_super_stockist"), 'class' => 'badge badge-info'],
            // 'draft_by_sub_stockist' => ['label' => __("messages.draft_by_sub_stockist"), 'class' => 'badge badge-light'],
        ];

        if ($cancel) {
            $status['order_cancelled'] = ['label' => __("messages.order_cancelled"), 'class' => 'badge badge-danger'];
        }

        return $status;
    }
}
