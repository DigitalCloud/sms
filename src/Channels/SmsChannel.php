<?php

namespace Digitalcloud\SMS\Channels;

use Digitalcloud\SMS\Exceptions\ProviderException;
use Digitalcloud\SMS\Interfaces\SMSNotifier;
use Digitalcloud\SMS\Models\SmsLog;
use Illuminate\Notifications\Notification;

class SmsChannel
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
        $sms = resolve(SMSNotifier::class);

        try {
            $result = $sms->notify($data['mobile'], $data['message']);

            if ($result !== true) {
                throw new ProviderException();
            } else {
                $notifiable->smsLogs()->create([
                    'notification' => get_class($notification),
                    'provider' => get_class($sms),
                    'mobile' => $data['mobile'],
                    'payload' => $data['unencoded_message'] ?? $data['message'],
                    'status' => SmsLog::STATUS_SUCCESS
                ]);
            }
        } catch (\Exception $exception) {
            $notifiable->smsLogs()->create([
                'notification' => get_class($notification),
                'message' => $exception->getMessage(),
                'provider' => get_class($sms),
                'mobile' => $data['mobile'],
                'payload' => $data['unencoded_message'] ?? $data['message'],
                'status' => SmsLog::STATUS_FAILED
            ]);

            if (method_exists($notification, "getShouldThrow") && $notification->getShouldThrow()) {
                throw $exception;
            }
        }
    }
}
