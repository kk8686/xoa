<?php
return [
	'enablePrettyUrl' => true,
	'showScriptName' => false,
	'rules' => [
		//系统
		'system/check-notice.do' => 'system/check-notice',
		
		//工作者
		'worker/register.do' => 'worker/register',
		'worker/login.do' => 'worker/login',
		'worker/logout.do' => 'worker/logout',
		'worker/check-login.do' => 'worker/check-login',
		'worker/headbar.json' => 'worker/headbar',

		//项目
		'project/list.json' => 'project/list',
		'project/<projectId:\d+>.json' => 'project/desc',
		'project/<projectId:\d+>/members.json' => 'project/members',
		'project/add.do' => 'project/add',
		'project/invite-member.do' => 'project/invite-member',

		//任务
		'task/add.do' => 'task/add-task',
		'task/<categoryId>/tasks.json' => 'task/list',
		'task/<taskId>.json' => 'task/info',
		'project/<projectId:\d+>/task-categorys.json' => 'task/task-categories',
		'task/move.do' => 'task/move-task',
		'task/update.do' => 'task/update-task',

		'<page:.+>.htm<xx:l{0,1}>' => 'site/r',
	],
];