<?php

return [
    'bins' => [
        'knife' => env('KNIFE_PATH', '/opt/chefdk/bin/knife')
    ],
    'conf' => [
        'kniferb' => env('KNIFE_RB_PATH', '/etc/santoku/knife.rb'),
        'ws' => [
            'address' => env('WS_ADDRESS', '192.168.10.10'),
            'port' => strval(env('WS_PORT', 3000)),
            'https' => env('WS_USE_HTTPS', false)
        ]
    ]
];