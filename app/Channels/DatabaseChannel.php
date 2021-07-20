<?php

namespace App\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon; 

class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function buildPayload($notifiable, Notification $notification)
    {   
        $notifiable->id = 0;
       
        return [
            // 'id' => $notification->id,
            'type' => get_class($notification),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => Carbon::now(),
            'tenant_id' => $notification->tenantId,
            'payment_id' => $notification->paymentId,
            'alert_type' => 'email'
        ];
    }
}