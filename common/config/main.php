<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                        'debug' => true,
                        //'cache' => false,
                    ],
                    'globals' => [
                        'html' => ['class' => '\yii\helpers\Html'],
                        'Declension' => 'frontend\components\Declension',
                        'Img' => 'frontend\components\Img',
                        'ImgFactory' => 'frontend\components\ImgFactory',
                        'YamapFactory' => 'frontend\components\YamapFactory',
                    ],
                    'uses' => ['yii\bootstrap'],
                    'extensions' =>['Twig_Extension_StringLoader', new \Twig_Extension_StringLoader(),new \Twig_Extension_Debug,]
                ],
                // ...
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'svadbanaprirode@yandex.ru',
                'password' => 'vitywhbzxzodifdf',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
            ],
        ],
    ],
];
