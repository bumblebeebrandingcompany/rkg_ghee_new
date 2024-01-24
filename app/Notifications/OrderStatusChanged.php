<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OrderStatusChanged extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($cc_array, $order, $boxes, $distributor, $item_html)
    {
        $this->order = $order;
        $this->boxes = $boxes;
        $this->distributor = $distributor;
        $this->item_html = $item_html;
        $this->cc_array = $cc_array;

        $this->role = 'Distributor';

        if ($distributor->role == 'wholesaler') {
            $this->role = 'Wholesaler';
        }

        if (!empty($this->order->sub_stockist_id)) {
            $this->role = 'Sub stockist';
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->order->order_status == 'order_placed') {
          
          if(empty($this->order->sub_stockist_id)){
            return (new MailMessage)
                ->cc($this->cc_array)
                ->greeting('Dear ' . $notifiable->company_name)
                ->line('Reference id. #' . $this->order->reference_id)
                ->line('' . $this->role . ' reference id: ' . $notifiable->reference_id)
                ->line('City: ' . $notifiable->address_city)
                ->line('Order details are as follow : ')
                ->line(new HtmlString($this->item_html))
                ->line('Additional Comments: ' . $this->order->distributor_notes)
                ->line('Total Box(s): ' . $this->boxes)
                ->line('Final Total Amount (Incl of GST) Rs. ' . number_format($this->order->total_price, 2))
                ->line('Thank you for your order!')
                ->subject('Order Placed, reference id: #' . $this->order->reference_id);
          }else{  
            return (new MailMessage)
                ->cc($this->cc_array)
                ->greeting('Dear ' . $notifiable->company_name)
                ->line('Reference id. #' . $this->order->reference_id)
                ->line('' . $this->role . ' reference id: ' . $notifiable->reference_id)
                ->line('City: ' . $notifiable->address_city)
                ->line('Order details are as follow : ')
                ->line(new HtmlString($this->item_html))
                ->line('Additional Comments: ' . $this->order->distributor_notes)
                ->line('Total Box(s): ' . $this->boxes)
                // ->line('Final Total Amount (Incl of GST) Rs. ' . number_format($this->order->total_price, 2))
                ->line('Thank you for your order!')
                ->subject('Order Placed, reference id: #' . $this->order->reference_id);
          }
          



                
        } elseif ($this->order->order_status == 'order_invoiced') {
            return (new MailMessage)
                ->cc($this->cc_array)
                ->greeting('Dear ' . $this->distributor->company_name)
                ->line('This is to inform you that your order with reference id #' . $this->order->reference_id . ' has been invoiced.')
                ->line('' . $this->role . ' reference id: ' . $this->distributor->reference_id)
                ->line('City: ' . $this->distributor->address_city)
                ->line('Order details are as follow : ')
                ->line(new HtmlString($this->item_html))
                ->line('Additional Comments: ' . $this->order->distributor_notes)
                ->line('Total Box(s): ' . $this->boxes)
                ->line('Final Total Amount (Incl of GST) Rs. ' . number_format($this->order->total_price, 2))
                ->subject('Order Invoiced, reference id: #' . $this->order->reference_id);
        } elseif ($this->order->order_status == 'order_dispatched') {
            return (new MailMessage)
                ->cc($this->cc_array)
                ->greeting('Dear ' . $this->distributor->company_name)
                ->line('Order with reference id #' . $this->order->reference_id . ' has been Dispatched.')
                ->line('' . $this->role . ' reference id: ' . $this->distributor->reference_id)
                ->line('City: ' . $this->distributor->address_city)
                ->line('Order details are as follow : ')
                ->line(new HtmlString($this->item_html))
                ->line('Additional Comments: ' . $this->order->distributor_notes)
                ->line('Total Box(s): ' . $this->boxes)
                ->line('Final Total Amount (Incl of GST) Rs. ' . number_format($this->order->total_price, 2))
                ->subject('Order Dispatched, reference id: #' . $this->order->reference_id);
        } elseif ($this->order->order_status == 'order_delivered') {
            return (new MailMessage)
                ->cc($this->cc_array)
                ->greeting('Dear ' . $this->distributor->company_name)
                ->line('Order with reference id #' . $this->order->reference_id . ' has been delivered.')
                ->line('' . $this->role . ' reference id: ' . $this->distributor->reference_id)
                ->line('City: ' . $this->distributor->address_city)
                ->line('Order details are as follow : ')
                ->line(new HtmlString($this->item_html))
                ->line('Additional Comments: ' . $this->order->distributor_notes)
                ->line('Total Box(s): ' . $this->boxes)
                ->line('Final Total Amount (Incl of GST) Rs. ' . number_format($this->order->total_price, 2))
                ->subject('Order Delivered, reference id: #' . $this->order->reference_id);
        } elseif ($this->order->order_status == 'pending_for_super_stockist') {
            return (new MailMessage)
                ->cc($this->cc_array)
                ->greeting('Dear ' . $this->distributor->company_name)
                ->line('Order with reference id #' . $this->order->reference_id . ' has been pending for super stockist.')
                ->line('' . $this->role . ' reference id: ' . $this->distributor->reference_id)
                ->line('City: ' . $this->distributor->address_city)
                ->line('Order details are as follow : ')
                ->line(new HtmlString($this->item_html))
                ->line('Additional Comments: ' . $this->order->distributor_notes)
                ->line('Total Box(s): ' . $this->boxes)
                ->line('Final Total Amount (Incl of GST) Rs. ' . number_format($this->order->total_price, 2))
                ->subject('Order pending for super, reference id: #' . $this->order->reference_id);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'new_status' => $this->order->order_status
        ];
    }
}
