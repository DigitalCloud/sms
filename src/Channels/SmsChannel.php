<?php

namespace Digitalcloud\SMS\Channels;

use Digitalcloud\SMS\Interfaces\SMSNotifier;
use Digitalcloud\SMS\Providers\Twilio;
use Digitalcloud\SMS\Models\SmsLog;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$notifiable->country || $notifiable->country->provider != Country::PROVIDER_UNIFONIC) {
            app()->bind(SMSNotifier::class, Twilio::class);
        }

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
        if (env("APP_ENV") == "testing" || !AdminSetting::isEnabled('sms_notifications')) {
            return;
        }

        $sms = resolve(SMSNotifier::class);

        try {
            $result = $sms->notify($data['mobile'], $data['message']);

            if ($result !== true) {
                throw new \Exception();
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
