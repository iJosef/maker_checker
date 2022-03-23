<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestApprovalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $admin_user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($admin_user)
    {
        $this->admin_user = $admin_user;
    }
}
