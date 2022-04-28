<?php
$moduleName = 'banketnye_zaly_moskva';
$params = array_merge(
    require __DIR__ . '/../../../common/config/params.php',
    require __DIR__ . '/../../../common/config/params-local.php',
    require __DIR__ . '/../params.php',
    require __DIR__ . '/../params-local.php',
    \common\utility\SiteParamsHelper::getParamsForModule($moduleName)
);
Yii::setAlias('@module_web', '@backend/modules/banketnye_zaly_moskva/web');

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__).'/..',
    'controllerNamespace' => 'backend\modules\banketnye_zaly_moskva\controllers',
    'bootstrap' => ['log','banketnye_zaly_moskva'],
    'modules' => [
        'banketnye_zaly_moskva' => [
            'class' => 'backend\modules\banketnye_zaly_moskva\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/modules/banketnye_zaly_moskva/views',
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
            'name' => 'advanced-backend',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=pmn_bzm',
            'username' => 'root',
            'password' => 'GxU25UseYmeVcsn5Xhzy',
            'charset' => 'utf8mb4',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'rules' => [
                ['pattern' => '/update', 'route' => 'update/update'],
                'media/<id:\d+>/resort/<sort:\d+>' => 'media/resort',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'blog-blocks',
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'blog-post-blocks',
                    'extraPatterns' => [
                        'POST sort' => 'sort',
                    ]
                ],
                ['pattern'=>'/update','route'=>'update/update'],
                '<controller>/<id:\d+>/<action>' => '<controller>/<action>',
                
            ],
        ],
    ],
    'params' => $params,
];
