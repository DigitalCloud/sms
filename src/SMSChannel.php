<?php

namespace Digitalcloud\SMS;

use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $this->validate($notification);

        $this->notify($notifiable, $notification);
    }

    protected function validate(Notification $notification)
    {
        if (!method_exists($notification, 'getMobile') || !$notification->getMobile()) {
            throw new \RuntimeException("No mobile number provided");
        }

        if (!method_exists($notification, 'getMessage') || !$notification->getMessage()) {
            throw new \RuntimeException("No message provided");
        }
    }

    private function notify($notifiable, $notification)
    {
        $sms = resolve(Provider::class);
        /**
         * @var  ProviderResponse $response
         */
        $response = $sms->notify($notification->getMobile(), $notification->getMessage());

        $response->log($notifiable, get_class($notification));

        if (method_exists($notification, "getShouldThrow") && $notification->getShouldThrow()) {
            $response->throw();
        }
    }
}
