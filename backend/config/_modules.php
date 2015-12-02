<?php

return [
    'gii' => [
        'generators' => [
            'form' => [
                'class' => 'yii\gii\generators\form\Generator',
                'templates' => [
                    'bootstrap' => '@app/components/generators/form/bootstrap',
                ],
            ],
        ],
    ],
    'webmaster' => [
        'class' => 'backend\modules\webmaster\Module',
    ],
];
