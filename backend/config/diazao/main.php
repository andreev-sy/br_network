<?php
$moduleName = 'diazao';
$moduleDBName = 'banket-brazil';
$params = array_merge(
    require __DIR__ . '/../../../common/config/params.php',
    require __DIR__ . '/../../../common/config/params-local.php',
    require __DIR__ . '/../params.php',
    require __DIR__ . '/../params-local.php',
    \common\utility\SiteParamsHelper::getParamsForModule($moduleName)
);
Yii::setAlias('@module_web', "@backend/modules/$moduleName/web");

return [
    'id' => 'app-backend',
    'language' => 'pt-BR',
    'basePath' => dirname(__DIR__) . '/..',
    'controllerNamespace' => "backend\modules\\$moduleName\controllers",
    'bootstrap' => ['log', "$moduleName"],
    'modules' => [
        "$moduleName" => [
            'class' => "backend\modules\\$moduleName\Module",
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => "@app/modules/$moduleName/views",
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'backend\modules\diazao\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host=localhost;dbname=$moduleDBName",
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
            'traceLevel' => YII_DEBUG ? 3 : 0,
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
        'translate' => [
            'class' => 'backend\modules\diazao\models\Translate',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'rules' => [
                // '<controller>' => '<controller>/index',
                // '<controller>/<id:\d+>/<action>' => '<controller>/<action>',
                // 'api/<any>' => 'site/error',
                // '<controller>/<action>' => '<controller>/<action>',
                '/translate/<lang:(ru-RU|pt-BR)>' => 'translate/index',
                '/restaurant/<type:(parsed|processed|archive)>' => 'restaurant/index',
                '/form-request/<type:(client|rest|partners)>' => 'form-request/index',
                '<controller>/<id:\d+>/<action>' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
