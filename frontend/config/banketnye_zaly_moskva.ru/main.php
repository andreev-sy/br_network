<?php
$params = array_merge(
    require __DIR__ . '/../../../common/config/params.php',
    require __DIR__ . '/../../../common/config/params-local.php',
    require __DIR__ . '/../params.php',
    require __DIR__ . '/../params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__).'/..',
    'bootstrap' => ['log', 'banketnye_zaly_moskva'],
    'controllerNamespace' => 'app\modules\banketnye_zaly_moskva\controllers',
    'modules' => [
        'banketnye_zaly_moskva' => [
            'class' => 'app\modules\banketnye_zaly_moskva\Module',
        ],
    ],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/modules/banketnye_zaly_moskva/views',
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'impl-stom@yandex.ru',
                'password' => 'gireveqbrlrwpofm',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=pmn_bzm',
            'username' => 'root',
            'password' => 'LP_db_',
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
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            'rules' => [
                '/' => 'site/index',
                ['pattern'=>'/catalog/<id:\d+>','route'=>'item/index', 'suffix'=>'/'],
                ['pattern'=>'/catalog/<slice>','route'=>'listing/slice', 'suffix'=>'/'],
                ['pattern'=>'/catalog/','route'=>'listing/index', 'suffix'=>'/'],
                ['pattern'=>'/ajax/filter-main','route'=>'listing/ajax-filter-slice', 'suffix'=>'/'],
                ['pattern'=>'/ajax/filter','route'=>'listing/ajax-filter', 'suffix'=>'/'],
                ['pattern'=>'/ajax/form','route'=>'form/validate', 'suffix'=>'/'],
                ['pattern'=>'/api/map_all','route'=>'api/mapall', 'suffix'=>'/'],
            ],
        ],
        
    ],
    'params' => $params,
];
