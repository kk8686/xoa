module.exports = {
	'project/<projectId:\\d+>.html' : 'project/dashboard.html',
	'project/<projectId:\\d+>/task-categorys.json' : '@mock/project/task-categorys.json',
	'project/<projectId:\\d+>/members.json' : '@mock/project/members.json',
	'project/<projectId:\\d+>.json' : '@mock/project/info.json',
	
	'task/<categoryId:\\d+>/tasks.json' : '@mock/task/tasks.json',
	'task/detail/<taskId:\\d+>.json' : '@mock/task/detail/detail.json'
};