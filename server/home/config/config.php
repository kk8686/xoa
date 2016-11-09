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
					'logFile' => '@runtime/logs/home-' . date('Y-m-d') . '.log'
                ],
            ],
        ],
        'db' => include(PROJECT_PATH . '/server/common/config/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				//工作者
				'worker/register.do' => 'worker/register',
				'worker/login.do' => 'worker/login',
				'worker/logout.do' => 'worker/logout',
				'worker/check-login.do' => 'worker/check-login',
				'worker/headbar.json' => 'worker/headbar',
				
				//项目
				'project/list.json' => 'project/list',
				'project/<projectId:\d+>.json' => 'project/info',
				'project/<projectId:\d+>/task-categorys.json' => 'project/task-categories',
				'project/add.do' => 'project/add',
				'project/invite-member.do' => 'project/invite-member',
				
				'<page:.+>.htm<xx:l{0,1}>' => 'site/r',
            ],
        ],
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
		]); //是否请求谁都可以访问的action
		
		if(!$isCommonAccessAction){
			$event->isValid = false;
			Yii::$app->response->redirect(Yii::$app->worker->loginUrl);
		}
	},
    'params' => [],
];

return $config;
