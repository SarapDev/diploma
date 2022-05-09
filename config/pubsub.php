<?php

return [
    'tables' => [
        'events' => 'pubsub_events',
    ],

    'listen' => [
        'UserAuth' => [
            'durable' => true,
            'listeners' => [
                \App\Listeners\UserAuthListener::class
            ],
        ],
    ],
];
