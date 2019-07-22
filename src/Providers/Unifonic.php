<?php

namespace Digitalcloud\SMS\Providers;

use Digitalcloud\SMS\Interfaces\SMSNotifier;

class Unifonic implements SMSNotifier
{
    protected static $sender;
    protected static $userAccount;
    protected static $passAccount;
    protected static $appId;
    const UN_SUPPORTED_STATUS_CODE = 21211;//eq to ER-11
    const UN_SUPPORTED_STATUS_CODE_STRING = 'ER-11';//eq to ER-11

    public function notify($mobileNo, $message)
    {
        if (starts_with($mobileNo, '+')) {
            $mobileNo = substr($mobileNo, 1);
        }

        self::send($mobileNo, $message);

        return true;
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
        if (strlen($number) == 10 && starts_with($number, '05')) {
            return preg_replace('/^0/', '966', $number);
        } elseif (starts_with($number, '00')) {
            return preg_replace('/^00/', '', $number);
        } elseif (starts_with($number, '+')) {
            return preg_replace('/^+/', '', $number);
        }

        return $number;
    }

    private static function Send($numbers, $msg)
    {
        static::run();
        $numbers = self::format_numbers($numbers);
        $id = self::$appId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.unifonic.com/rest/Messages/Send');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "AppSid={$id}&Recipient={$numbers}&Body={$msg}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);

        if (isset($response->success) && strtolower($response->success) == 'true') {
            return true;
        }

        $statusCode = 0;

        if ($response->errorCode === self::UN_SUPPORTED_STATUS_CODE_STRING) {
            $statusCode = self::UN_SUPPORTED_STATUS_CODE;
        }

        throw new \Exception($response->message ?? '', $statusCode);
    }

    private static function run()
    {
        static::$sender = config('uniform.sender');
        static::$userAccount = config('uniform.username');
        static::$passAccount = config('uniform.password');
        static::$appId = config('uniform.app_id');
    }
}
