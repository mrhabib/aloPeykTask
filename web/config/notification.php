<?php

return [
    'sms_provider' => env('DEFAULT_SMS_PROVIDER', 'twilio'), // Default SMS provider
    'email_provider' => env('DEFAULT_EMAIL_PROVIDER', 'sendgrid'), // Default email provider
];
