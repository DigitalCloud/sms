# SMS Notifier module for laravel

## Features
* Supports multiple SMS providers (twilio and unifonic).
* A channel for sending SMS notifications.
* Logging.
* A unified response for all providers.


##Installation
Require the `digitalcloud/sms` package in your `composer.json` and update your dependencies:
```sh
    "require": {
        "digitalcloud/sms": "dev-master"
    }
    "repositories": [
        {
          "type": "vcs",
          "url": "https://github.com/digitalCloud/sms"
        }
    ]
```


after installing the package you should migrate the tables using this command:
 ```sh
 $ php artisan migrate
 ```
 
finally, publish the config using this command:
  ```sh
  $ php artisan vendor:publish --provider="Digitalcloud\SMS\ServiceProvider"
  ```
 
## Usage
* Each model that use sms notifications should use `Digitalcloud\SMS\HasSmsLogs` trait
to allow logging.

* Add `routeNotificationForSms` in your notifiable model
```php
<?php

class User extends Model {
    public function routeNotificationForSms(){
        return $this->mobile;
    }
}
```
alternatively you can add `getMobile` function inside your notification class, so the mobile number will be taken from the `getMobile` not from `routeNotificationForSms`.

* In your notification class include the SMS channel in the via function,
also `toSMS` function.

* You can add `getShouldThrow` function into your notification class to
 allow channel to throw an instance of `ProviderException` if the provider request has been failed.

```php
<?php

use Digitalcloud\SMS\SMSChannel;

class YourNotificationClass extends Notification {
    
    public function via()
    {
        return [SMSChannel::class];
    }
    
    public function toSms(){
        return 'Your activation code is 6523';
    }
    
    //optional
    public function getMobile(){
        return '0599865326';
    }
    
    //optional
    public function getShouldThrow(){
        return true;
    }
}
```