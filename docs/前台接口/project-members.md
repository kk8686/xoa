项目 - 所有成员
===

- 地址：GET `/project/$项目ID/members.json`

	示例：/project/1/members.json
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : [
			{
				id : 1,
				name : '名称1',
				avatar : '/data/worker/avatar/1.jpg'
			},
			{
				id : 2,
				name : '名称2',
				avatar : '/data/worker/avatar/2.jpg'
			},
			{
				id : 3,
				name : '名称3',
				avatar : '/data/worker/avatar/3.jpg'
			},
		]
	}
	```