<?php

namespace App\Listeners;

use App\Events\NotificationTriggered;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CreateGenericNotification
{
    public function handle(NotificationTriggered $event)
    {
        // Skip if the recipient is the submitter (e.g., owner submitting own report)
        if ($event->userId !== Auth::id()) {
            Notification::create([
                'user_id' => $event->userId,
                'notifiable_id' => $event->notifiable->id,
                'notifiable_type' => get_class($event->notifiable),
                'farm_id' => $event->farmId,
                'type' => $event->type,
                'title' => $event->title,
                'message' => $event->message,
                'data' => $event->data,
                'is_read' => false,
            ]);
        }
    }
}
