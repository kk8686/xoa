<?php
$config = [
    'id' => 'xoa',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'db' => include(PROJECT_PATH . '/server/common/config/db.php'),
        'urlManager' => include(__DIR__ . '/url.php'),
    ],
	'on beforeAction' => function($event){
		//暂时在此做统一登陆校验
		if(!Yii::$app->worker->isGuest){
			return;
		}
		$isCommonAccessAction = in_array($event->action->controller->module->requestedRoute, [
			'worker/register',
			'worker/login',
			'worker/check-login',
			'site/error',
			'site/captcha',
			'site/r',
		]); //是否不登陆可以访问
		
		if(!$isCommonAccessAction){
			$event->isValid = false;
			Yii::$app->response->redirect(Yii::$app->worker->loginUrl);
		}
	},
    'params' => [],
];

return $config;
