任务 - 列表
===

- 地址：GET `/task/list.json`

- 参数：

	- `projectId`：项目ID

	- `categoryId`：任务分类ID
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : [
			{
				id : 1,
				title : '开发功能A',
				limit_time : '2016-12-12 18:00:00',
				worker_avatar : '/data/worker/avatar/1.jpg',
			}
		]
	}
	```