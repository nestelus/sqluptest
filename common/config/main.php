<?php
return [
    'language' => 'ru-RU',
    'name' => 'Тест',
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => FALSE,
            'rules' => [

                '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
                '<controller>/<action>' => '<controller>/<action>',
                '<module>/<controller>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module>/<controller>/<action>' => '<module>/<controller>/<action>',
            ],
        ],
    ],
];
