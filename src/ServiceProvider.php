<?php

namespace Digitalcloud\SMS;

use Digitalcloud\SMS\Interfaces\SMSNotifier;
use Digitalcloud\SMS\Providers\Twilio;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . './../config/sms.php' => config_path('sms.php'),
        ], 'digitalcloud-sms');

        $this->loadMigrationsFrom(__DIR__ . './../database/migrations');

        $this->app->bind(SMSNotifier::class, Twilio::class);
    }
}