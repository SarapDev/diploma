<?php

use App\Listeners\UserAuthListener;

return [
    'tables' => [
        'events' => 'pubsub_events',
    ],

    'listen' => [
        'UserAuth' => [
            'durable' => true,
            'listeners' => [
                UserAuthListener::class
            ],
        ],
    ],
];
