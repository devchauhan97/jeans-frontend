<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendProductOrderMail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $orders_id;
    public $orders_data;
    public $total_price;
    public $subtotal;
    public function __construct($orders_id)
    {
        $this->orders_id   = $orders_id;
        // $this->orders_data = $orders_data;
        // $this->total_price = $total_price;
        // $this->subtotal    = $subtotal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return [];
       // return new PrivateChannel('channel-name');
    }
}
