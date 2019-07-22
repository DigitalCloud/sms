<?php

namespace Digitalcloud\SMS\Providers;

use Digitalcloud\SMS\Classes\ProviderResponse;
use Digitalcloud\SMS\Interfaces\SMSNotifier;
use Illuminate\Support\Str;

class Unifonic implements SMSNotifier
{
    public function notify($mobileNo, $message): ProviderResponse
    {
        if (Str::startsWith($mobileNo, '+')) {
            $mobileNo = substr($mobileNo, 1);
        }

        $resp = self::send($mobileNo, $message);

        if (strtolower($resp->success) === "true") {
            return ProviderResponse::make()
                ->setMobile($mobileNo)
                ->setProvider(self::class)
                ->setMessage($message)
                ->setSuccess(true);
        } else {
            return ProviderResponse::make()
                ->setMobile($mobileNo)
                ->setProvider(self::class)
                ->setMessage($message)
                ->setSuccess(false)
                ->setResponse($resp->message)
                ->setCode($resp->errorCode);
        }
    }

    private static function send($numbers, $msg)
    {
        $numbers = self::format_numbers($numbers);
        $appId = config('sms.drivers.unifonic.app_id');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://api.unifonic.com/rest/Messages/Send');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "AppSid={$appId}&Recipient={$numbers}&Body={$msg}");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    private static function format_numbers($numbers)
    {
        if (!is_array($numbers)) {
            return self::format_number($numbers);
        }

        $numbers_array = [];

        foreach ($numbers as $number) {
            $n = self::format_numbers($number);
            array_push($numbers_array, $n);
        }

        return implode(',', $numbers_array);
    }

    private static function format_number($number)
    {
        if (strlen($number) == 10 && Str::startsWith($number, '05')) {
            return preg_replace('/^0/', '966', $number);
        } elseif (Str::startsWith($number, '00')) {
            return preg_replace('/^00/', '', $number);
        } elseif (Str::startsWith($number, '+')) {
            return preg_replace('/^+/', '', $number);
        }

        return $number;
    }
}
