<?php

namespace App\Classes;

interface SMSNotifier
{
    public function notify($mobileNo, $message);
}
