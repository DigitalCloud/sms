<?php

namespace Digitalcloud\SMS\Interfaces;

interface SMSNotifier
{
    public function notify($mobileNo, $message);
}
