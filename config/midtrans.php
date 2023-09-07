<?php

$is_production = env('IS_PRODUCTION');

if ($is_production) {
    return [
        'server_key' => env('MIDTRANS_SERVER_KEY_PROD'),
        'client_key' => env('MIDTRANS_CLIENT_KEY_PROD'),
        
        'snap_request_url' => 'https://app.midtrans.com/snap/v1/transactions',
        'snap_url' => 'https://app.midtrans.com/snap/snap.js'
    ];
}
else {
    return [
        'server_key' => env('MIDTRANS_SERVER_KEY_SANDBOX'),
        'client_key' => env('MIDTRANS_CLIENT_KEY_SANDBOX'),
        
        'snap_request_url' => 'https://app.sandbox.midtrans.com/snap/v1/transactions',
        'snap_url' => 'https://app.sandbox.midtrans.com/snap/snap.js'
    ];
}
