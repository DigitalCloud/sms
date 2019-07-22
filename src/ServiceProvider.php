<?php

namespace Digitalcloud\SMS;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . './../config/sms.php' => config_path('sms.php'),
        ], 'digitalcloud-sms');

        $this->publishes([
            __DIR__ . './../resources/lang' => resource_path('lang/vendor/sms'),
        ], 'digitalcloud-sms');

        $this->loadMigrationsFrom(__DIR__ . './../database/migrations');
    }
}