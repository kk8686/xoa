<?php
return [
	'aliases' => [
		'@xoa_test' => dirname(__DIR__),
	],
	'components' => [
		'db' => [
			'dsn' => 'sqlite:'. PROJECT_PATH . '/server/data/database-test.db'
		],
	],
];