<?php

return [

    "default" => env('SMS_DEFAULT_DRIVER'),

    "drivers" => [
        "unifonic" => [
            'app_id' => env('SMS_UNIFONIC_APP_ID')
        ],

        'twilio' => [
            'auth_token' => env('SMS_TWILIO_AUTH_TOKEN'),
            'account_sid' => env('SMS_TWILIO_ACCOUNT_SID'),
            'from_name' => env('SMS_TWILIO_SENDER'),
            'from_number' => env('SMS_TWILIO_MOBILE')
        ]
    ]
];