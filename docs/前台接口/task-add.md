任务 - 添加
===

- 地址：POST `/task/add.do`

- 参数：

	- `title`：任务标题，4到30字

	- `detail`：任务详情，可选，不为空的时候必须是4到65535字之间
	
	- `workerIds`：负责人ID数组
	
	- `relatedMemberIds`：相关人员ID数组
	
	- `limitTime`：限制完成时间，示例：**2012-12-12 18:00**
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : {
			id : 11,
			title : '任务标题',
			limit_time : '2012-12-12 18:00',
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
			]
		}
	}
	```