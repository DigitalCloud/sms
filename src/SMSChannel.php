<?php

namespace Digitalcloud\SMS;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $this->notify($notifiable, $notification);
    }

    protected function getData($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toSMS')) {
            return $notification->toSMS($notifiable);
        }

        if (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new \RuntimeException(
            'Notification is missing toSMS / toArray methods.'
        );
    }

    private function notify($notifiable, $notification)
    {
        if (!config('sms.default'))
            return;
        
        $data = $this->getData($notifiable, $notification);

        $message = is_array($data) ? Arr::first($data) : $data;

        $mobile = method_exists($notification, 'getMobile') ? $notification->getMobile() : $notifiable->routeNotificationForSMS();

        $sms = resolve(Provider::class);
        /**
         * @var  ProviderResponse $response
         */
        $response = $sms->notify($mobile, $message);

        $response->log($notifiable, get_class($notification));

        if (method_exists($notification, "getShouldThrow") && $notification->getShouldThrow()) {
            $response->throw();
        }
    }
}
