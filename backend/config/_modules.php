<?php

return [
    'gii' => [
        'generators' => [
            'form' => [
                'class' => yii\gii\generators\form\Generator::class,
                'templates' => [
                    'bootstrap' => '@app/components/generators/form/bootstrap',
                ],
            ],
        ],
    ],
    'webmaster' => [
        'class' => backend\modules\webmaster\Module::class,
    ],
    'blog' => [
        'class' => backend\modules\blog\Module::class,
    ],
];
