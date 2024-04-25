<?php

$dBName = 'brazil';
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Diazao',
    'language' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'RbacActionTemplate' => [
            'class' => 'backend\components\RbacActionTemplate',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => "@app/views",
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'class' => 'yii\web\Session',
            'name' => 'advanced-backend',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host=localhost;dbname=$dBName",
            'username' => 'pmnetwork',
            'password' => 'P6L19tiZhPtfgseN',
            'charset' => 'utf8',
        ],
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'autodetectCluster' => false,
            'nodes' => [
                ['http_address' => '127.0.0.1:9200'],
                // configure more hosts if you have a cluster
            ],
        ],
        'log' => [
            // 'traceLevel' => YII_DEBUG ? 3 : 0,
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error', 
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
            'cache' => 'cache',
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],
                'app' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'sourceMessageTable' => 'source_message',
                    'sourceLanguage' => 'ru-RU',
                    'on missingTranslation' => ['backend\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
                // 'app*' => [
                //     'class' => 'yii\i18n\PhpMessageSource',
                //     'basePath' => '@app/translations',
                //     'sourceLanguage' => 'ru-RU',
                //     'fileMap' => [
                //         'app'       => 'app.php',
                //         'app/error' => 'error.php',
                //     ],
                //     'on missingTranslation' => ['backend\components\TranslationEventHandler', 'handleMissingTranslation']
                // ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller>' => '<controller>/index',
                '<controller>/<id:\d+>/<action>' => '<controller>/<action>',

                // ['pattern'=>'/update','route'=>'update/update'],
            ],
        ],
    ],
    'params' => $params,
    'as access' => [
        'class' => yii2mod\rbac\filters\AccessControl::class,
        'allowActions' => [
            'site/*',
            'debug/default/toolbar',
        ]
     ],
    'on beforeAction'=>function($event){
        $supportedLanguages = [
            'ru-RU',
            'pt-BR',
        ];

        $cookies = Yii::$app->request->cookies;
        $language = $cookies->getValue('language');
        $preferredLanguage = !empty($language) ? $language : Yii::$app->request->getPreferredLanguage($supportedLanguages);
    
        Yii::$app->language = $preferredLanguage;

        Yii::$app->params['ru'] = $preferredLanguage === 'ru-RU' ? true : false;
        Yii::$app->params['4u_domain'] = 'https://4u.diazao.com.br/';
        // Yii::$app->params['4u_domain'] = 'http://4u.dev.com.br/';
    }, 
];
