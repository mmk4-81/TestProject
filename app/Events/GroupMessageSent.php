<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('group.' . $this->message->group_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->text,
            'id' => auth()->user()->id,
            'user' => auth()->user()->name,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'server.created';
    }

}
