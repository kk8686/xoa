<?php
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'runtimePath' => '@app/../runtime',
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
				'worker/headbar.json' => 'worker/headbar',
				
				'project/list.json' => 'project/list',
				'project/<projectId:\d+>.json' => 'project/info',
				'project/<projectId:\d+>/task-categorys.json' => 'project/categorys',
				'project/add.do' => 'project/add',
				
				'<page:.+>.htm<xx:l{0,1}>' => 'site/r',
            ],
        ],
    ],
	'on beforeAction' => function($event){
		$isNeedentLoginAction = in_array($event->action->controller->module->requestedRoute, [
			'worker/register',
			'worker/login',
			'worker/check-login',
			'site/error',
			'site/captcha',
			'site/r',
		]); //是否无须登陆的action
		
		if(!$isNeedentLoginAction && Yii::$app->worker->isGuest){
			$event->isValid = false;
			header('xx:yy');
			Yii::$app->response->redirect(Yii::$app->worker->loginUrl);
		}
	},
    'params' => [],
];

return $config;
