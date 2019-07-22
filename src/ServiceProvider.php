<?php

namespace Digitalcloud\SMS;

use Digitalcloud\SMS\Providers\Twilio;
use Digitalcloud\SMS\Providers\Unifonic;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . './../config/sms.php' => config_path('sms.php'),
        ], 'digitalcloud-sms');

        $this->loadMigrationsFrom(__DIR__ . './../database/migrations');

        if ($default = config("sms.default")) {
            $this->app->bind(Provider::class, self::getProviderFromDriver($default));
        }
    }

    public static function getProviderFromDriver($driver)
    {
        switch ($driver) {
            case "unifonic":
                return Unifonic::class;
                break;
            case "twilio":
                return Twilio::class;
                break;
            default:
                throw new ProviderException("Provider doesnt exists", 500);
                break;
        }
    }
}