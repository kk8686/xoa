<?php
$serverPath = dirname(__DIR__);
$config = [
    'id' => 'basic',
    'basePath' => $serverPath,
    'bootstrap' => ['log'],
	'aliases' => [
		'@webRoot' => $serverPath . '/../../web/home',
	],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '121212',
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'xoa\models\User',
            'enableAutoLogin' => true,
			'loginUrl' => '/user/login.html',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'xoa\common\ext\log\FileLog',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'db' => include(PROJECT_PATH . '/server/common/config/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'user/register.do' => 'user/register',
				'user/login.do' => 'user/login',
				'user/login.html' => 'user/show-login',
            ],
        ],
    ],
    'params' => [],
];

return $config;
