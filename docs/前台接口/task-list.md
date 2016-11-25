任务 - 列表
===

- 地址：GET `/task/$任务分类ID/tasks.json`
	
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
				workers : [
					{
						name :'小明',
						avatar : '/data/worker/avatar/1.jpg'
					},
					{
						name :'小红',
						avatar : '/data/worker/avatar/2.jpg'
					}
				],
			}
		]
	}
	```