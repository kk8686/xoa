任务 - 添加子任务
===

- 地址：POST `/task/add-child.do`

- 参数：

	- `title`：任务标题，4到30字
	
	- `workerId`：负责人ID
	
	- `limitTime`：限制完成时间，示例：**2012-12-12 18:00**
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : {
			id : 11,
			title : '子任务标题',
			limit_time : '2012-12-12 18:00',
			worker : {
				name : '负责人名称',
				avatar : '/data/worker/avatar/1.jpg'
			}
		}
	}
	```