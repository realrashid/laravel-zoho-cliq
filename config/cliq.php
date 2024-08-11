<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Zoho Cliq API.
    |
    */
    'api_base_url' => env('CLIQ_API_BASE_URL', 'https://cliq.zoho.com/api/v2'),

    /*
    |--------------------------------------------------------------------------
    | OAuth Credentials
    |--------------------------------------------------------------------------
    |
    | These are OAuth credentials required for authentication with Zoho Cliq API.
    | You can obtain these credentials from your Zoho Cliq Developer Console:
    | https://api-console.zoho.com/
    |
    */
    'client_id' => env('CLIQ_CLIENT_ID', ''),
    'client_secret' => env('CLIQ_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Channel
    |--------------------------------------------------------------------------
    |
    | The default channel or user ID to post messages to.
    | It can be a channel like #default, a private #group, or a @username.
    |
    */
    'channel' => env('CLIQ_DEFAULT_CHANNEL', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Send To
    |--------------------------------------------------------------------------
    |
    | The default target type for sending messages.
    | Options: channelsbyname, bots, chats, buddies
    |
    */
    'send_to' => env('CLIQ_DEFAULT_SEND_TO', 'buddies'),
];
