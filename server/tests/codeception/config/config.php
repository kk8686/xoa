<?php
return [
	'aliases' => [
		'@xoa_test' => dirname(__DIR__),
	],
	'components' => [
		'db' => [
			'dsn' => 'sqlite:'. '@xoa/data/database-test.db'
		],
	],
];