<?php

namespace Digitalcloud\SMS\Providers;

use Digitalcloud\SMS\Classes\ProviderResponse;
use Digitalcloud\SMS\Interfaces\SMSNotifier;
use Illuminate\Support\Str;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;

class Twilio implements SMSNotifier
{
    private $from;
    private $tries;
    private $client;

    const UN_SUPPORTED_STATUS_CODE = 21211;

    public function __construct()
    {
        $this->from = config('sms.drivers.twilio.from_number');
        $this->tries = 0;
        $this->client = new Client(config('sms.drivers.twilio.account_sid'), config('sms.drivers.twilio.auth_token'));
    }

    public function notify($mobileNo, $message): ProviderResponse
    {
        try {
            if (!Str::startsWith($mobileNo, '+')) {
                $mobileNo = '+' . $mobileNo;
            }

            $this->tries++;

            $this->client->messages->create($mobileNo, ['from' => $this->from, 'body' => $message]);

            return ProviderResponse::make()
                ->setProvider(self::class)
                ->setSuccess(true)
                ->setMessage($message)
                ->setMobile($mobileNo);

        } catch (RestException $exception) {
            //if destination not support names, re send using mobile number
            if ($exception->getCode() == 21612 && $this->tries == 1) {
                $this->from = config('sms.drivers.twilio.from_number');
                return $this->notify($mobileNo, $message);
            } else {
                return ProviderResponse::make()
                    ->setProvider(self::class)
                    ->setCode($exception->getCode())
                    ->setSuccess(false)
                    ->setResponse($exception->getMessage())
                    ->setMessage($message)
                    ->setMobile($mobileNo);
            }
        }
    }
}
