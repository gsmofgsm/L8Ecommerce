<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // from is set in env MAIL_FROM_ADDRESS MAIL_FROM_NAME
        return $this->to($this->order->billing_email, $this->order->billing_name)
            ->bcc('another@another.com')
            ->subject('Order for Laravel Ecommerce Example')
            ->markdown('emails.orders.placed');
    }
}
