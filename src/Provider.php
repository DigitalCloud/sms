<?php

namespace Digitalcloud\SMS;


interface Provider
{
    public function notify($mobileNo, $message): ProviderResponse;
}
