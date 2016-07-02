<?php

return [
    [
        'controller' => ['site', 'test'],
    ],
    [
        'controller' => 'v1/auth',
        'pluralize' => false,
        'patterns' => [
            'POST login' => 'login',
            'POST register' => 'register',
        ],
    ],
    [
        'controller' => 'v1/upload',
        'pluralize' => false,
        'patterns' => [
            'POST test' => 'test-upload',
            'POST callback' => 'callback',
            'POST file-delete' => 'file-delete',
            'GET <action:\w+>' => '<action>',
        ],
    ],
];
