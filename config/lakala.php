<?php
return [

    'default_driver' => 'master',

    'table_names' => [

//        'configs' => 'lakala_configs',

        'logs' => 'lakala_send_logs',

    ],

    'driver' => [

        'master' => [
            'merchant_id' => '872290021025000',
            'key_rsa_path' => storage_path('872290021025000.p12'),
            'key_rsa_pass' => '788288',
            'notify_url' => 'http://www.***.fun/callback',
        ],

    ]
];
