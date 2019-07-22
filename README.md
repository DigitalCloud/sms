# Digitalcloud SMS Notifier module

## Features
* Supports multiple SMS providers (twilio and unifonic).
* A channel for sending SMS notifications.
* Log the response returned from provider for each request.
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
* in your notification class include the SMS channel in via function,
also `getMobile` and `getMessage` functions.
```php
<?php

use Digitalcloud\SMS\SMSChannel;

class YourNotificationClass extends Notification {
    
    public function via()
    {
        return [SMSChannel::class];
    }
    
    public function getMobile(){
        return '970567940999';
    }
    
    public function getMessage(){
        return 'Your activation code is 6523';
    }
}
```