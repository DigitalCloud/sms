<?php

return [

    "default" => env('SMS_DEFAULT_DRIVER'),

    "drivers" => [
        "mobily" => [
            "driver" => "mobily",
            "provider" => \Digitalcloud\SMS\Providers\Mobily::class,
            'sender' => env('SMS_MOBILY_SENDER'),
            'mobile' => env('SMS_MOBILY_MOBILE'),
            'password' => env('SMS_MOBILY_PASSWORD'),
            'deleteKey' => env('SMS_MOBILY_DELETE_KEY'),
            'resultType' => 1,
            'viewResult' => 1,
            'MsgID' => 0
        ],

        "unifonic" => [
            "driver" => "unifonic",
            "provider" => \Digitalcloud\SMS\Providers\Unifonic::class,
            'sender' => env('SMS_UNIFONIC_SENDER'),
            'username' => env('SMS_UNIFONIC_USERNAME'),
            'password' => env('SMS_UNIFONIC_PASSWORD'),
            'app_id' => env('SMS_UNIFONIC_APP_ID')
        ],

        'twilio' => [
            "driver" => "twilio",
            "provider" => \Digitalcloud\SMS\Providers\Twilio::class,
            'auth_token' => env('SMS_TWILIO_AUTH_TOKEN'),
            'account_sid' => env('SMS_TWILIO_ACCOUNT_SID'),
            'from_name' => env('SMS_TWILIO_SENDER'),
            'from_number' => env('SMS_TWILIO_MOBILE')
        ]
    ]
];