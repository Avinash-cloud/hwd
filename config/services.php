<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY_ID', 'rzp_test_dummy_key_12345'),
        'secret' => env('RAZORPAY_KEY_SECRET', 'dummy_secret_67890'),
        'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET', 'dummy_webhook_secret'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', 'dummy_google_client_id.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', 'dummy_google_client_secret'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://localhost:8000/auth/google/callback'),
    ],

    'nimbuspost' => [
        'base_url' => rtrim((string) env('NIMBUSPOST_BASE_URL', 'https://api-v2.nimbuspost.com'), '/'),
        'api_key' => env('NIMBUSPOST_API_KEY'),
        'api_secret' => env('NIMBUSPOST_API_SECRET'),
        'warehouse_id' => env('NIMBUSPOST_WAREHOUSE_ID', '7f8a72aa-e52b-4916-b67b-23e78cba50bc'),
        'warehouse_code' => env('NIMBUSPOST_WAREHOUSE_CODE', 'WH-001'),
        'warehouse_name' => env('NIMBUSPOST_WAREHOUSE_NAME', 'Haridwar Bliss Warehouse'),
        'webhook_secret' => env('NIMBUSPOST_WEBHOOK_SECRET', 'haridwar_bliss_nimbus_secret_2026'),
        'auto_create_shipment' => env('NIMBUSPOST_AUTO_CREATE_SHIPMENT', true),
        'mock_mode' => env('NIMBUSPOST_MOCK_MODE', false),
    ],

];
