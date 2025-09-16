<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Database\Eloquent\Model;

class NotificationTriggered
{
    use Dispatchable;

    public $type;
    public $notifiable;
    public $userId;
    public $farmId;
    public $title;
    public $message;
    public $data;

    public function __construct(string $type, Model $notifiable, int $userId, ?int $farmId, string $title, string $message, array $data = [])
    {
        $this->type = $type;
        $this->notifiable = $notifiable;
        $this->userId = $userId;
        $this->farmId = $farmId;
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
    }
}
