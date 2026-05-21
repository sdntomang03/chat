<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Wajib ShouldBroadcastNow
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    // Tentukan saluran mana yang menerima pesan (ID Penerima)
    public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->message->receiver_id);
    }

    // Paksa nama event agar tidak membingungkan frontend
    public function broadcastAs()
    {
        return 'MessageSent';
    }

    // Data apa saja yang dikirim ke Pusher
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'content' => $this->message->content,
            'type' => $this->message->type,
            'file_path' => $this->message->file_path,
        ];
    }
}
