<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Pusher\Pusher;
use Pusher\PusherException;

class LogNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {
        $instance = $event->notifiable->notifications->where('id', $event->notification->id)->first();
        if (isset($instance->data['notification_text_id'])){
            $instance->notification_text_id = $instance->data['notification_text_id'];
            $instance->save();
//            $options = array(
//                'cluster' => 'us2',
//                'encrypted' => true
//            );
//            $pusher = new Pusher();
//            $data['notification'] = $instance->text;
//            try {
//                $pusher->trigger('notify_' . $event->notifiable->id, 'new-notification',$instance->data);
//            } catch (PusherException $e) {
//
//            }
        }else if (isset($instance)){
            $instance->delete();
        }
    }
}