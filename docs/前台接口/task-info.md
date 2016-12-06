任务 - 信息
===

- 地址: GET `/task/$任务ID.json`

- 参数：

	- `taskId`: 任务ID
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : {
			id : 11,
			title : '任务标题',
			limit_time : '2012-12-12 18:00',
			level : 3,	//任务级别
			repeat : 1,	//重复周期
			
			workers : [
				//负责人
				{
					id : 11,
					name : '负责人1称呼',
					avatar : '/data/worker/avatar/1.jpg'
				},
				{
					id : 22,
					name : '负责人2称呼',
					avatar : '/data/worker/avatar/2.jpg'
				},
				//更多人员
			],
			
			related_members : [
				//相关人员
				{
					id : 11,
					name : '相关人员1称呼',
					avatar : '/data/worker/avatar/1.jpg'
				},
				{
					id : 22,
					name : '相关人员2称呼',
					avatar : '/data/worker/avatar/2.jpg'
				},
				//更多人员
			],
			
			'history' => [
				//任务历史事件，详细内容未确定，可以先不显示历史
				{
					id : 1,
					time : 1, //发生时间
					type : 1, //事件类型
					desc : 'KK 修改了任务内容为 $任务内容的前200字', //事件描述
				}
			],
		}
	}
	```