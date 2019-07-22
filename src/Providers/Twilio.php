<?php

namespace Digitalcloud\SMS\Providers;

use Digitalcloud\SMS\Interfaces\SMSNotifier;
use Exception;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class Twilio implements SMSNotifier
{
    private $sid;
    private $token;
    private $from;
    private $tries;
    private $client;

    const UN_SUPPORTED_STATUS_CODE = 21211;

    public function __construct()
    {
        $this->sid = config('sms.twilio.account_sid');
        $this->token = config('sms.twilio.auth_token');
        $this->from = config('sms.twilio.from_number');
        $this->tries = 0;
        $this->client = new Client($this->sid, $this->token);
    }

    public function notify($mobileNo, $message)
    {
        try {
            if (!Str::startsWith($mobileNo, '+')) {
                $mobileNo = '+' . $mobileNo;
            }

            $this->tries++;
            $this->client->messages->create($mobileNo, ['from' => $this->from, 'body' => $message]);

            return true;
        } catch (Exception $exception) {

            if ($exception->getCode() == 21612 && $this->tries == 1) {
                $this->from = config('sms.twilio.from_number');

                return $this->notify($mobileNo, $message);
            }

            throw $exception;
        }
    }
}
