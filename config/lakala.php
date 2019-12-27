<?php
return [

    'default_driver' => 'master',

    'table_names' => [

        'configs' => 'lakala_configs',

        'logs' => 'lakala_send_logs',

    ],

    'driver' => [

        'master' => [
            'merchant_id' => env('LAKALA_MERCHANT_ID', '872100003015000'),
            'key_rsa_path' => storage_path(env('LAKALA_MERCHANT_ID', '872100003015000').'.p12'),
            'key_rsa_pass' => env('LAKALA_', '015560'),
            'notify_url' => env('NOTIFY_URL', ''),
            'page_notify_url' => env('RETURN_URL', ''),
        ],

    ]
];
