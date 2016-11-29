<?php
return [
	'runtimePath' => PROJECT_PATH . '/runtime',
	'aliases' => [
		'@project' => PROJECT_PATH,
		'@xoa' => PROJECT_PATH . '/server',
		'@home' => PROJECT_PATH . '/server/home',
		'@backend' => PROJECT_PATH . '/server/backend',
		'@console' => PROJECT_PATH . '/server/console',
		'@hook-yii2' => PROJECT_PATH . '/server/common/hook-yii2',
	],
	'components' => [
        'worker' => [
			'class' => 'yii\web\User',
            'identityClass' => 'xoa\common\models\Worker',
            'enableAutoLogin' => true,
			'loginUrl' => '/login.html',
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
	],
];