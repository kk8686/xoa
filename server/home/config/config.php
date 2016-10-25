<?php
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'aliases' => [
		'@webPath' => PROJECT_PATH . '/web/home',
	],
	'controllerNamespace' => 'xoa\home\controllers',
    'components' => [
        'request' => [
            'cookieValidationKey' => '121212',
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'worker' => [
			'class' => 'yii\web\User',
            'identityClass' => 'xoa\common\models\Worker',
            'enableAutoLogin' => true,
			'loginUrl' => '/login.html',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'xoa\common\ext\log\FileLog',
                    'levels' => ['error', 'warning', 'info'],
					'logFile' => '@runtime/logs/' . date('Y-m-d') . '.log'
                ],
            ],
        ],
        'db' => include(PROJECT_PATH . '/server/common/config/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'worker/register.do' => 'worker/register',
				'worker/login.do' => 'worker/login',
				'worker/check-login.do' => 'worker/check-login',
				
				'project/list.json' => 'project/list',
				
				'<page:\w+>.htm' => 'worker/check-login',
            ],
        ],
    ],
    'params' => [],
];

return $config;
