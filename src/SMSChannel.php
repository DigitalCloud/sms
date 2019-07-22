<?php

namespace Digitalcloud\SMS;

use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $this->notify($notifiable, $notification, $this->getData($notifiable, $notification));
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
            'Notification is missing toSMS / toArray method.'
        );
    }

    private function notify($notifiable, $notification, $data)
    {
        $sms = resolve(Provider::class);
        /**
         * @var  ProviderResponse $response
         */
        $response = $sms->notify($data['mobile'], $data['message']);

        $response->log($notifiable, get_class($notification));

        if (method_exists($notification, "getShouldThrow") && $notification->getShouldThrow()) {
            $response->throw();
        }
    }
}
