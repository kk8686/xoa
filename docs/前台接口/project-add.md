项目 - 添加
===

- 地址：POST `/project/add.do`

	示例：/project/add.do

- 参数

	- `name`：项目名称
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : {
			id : 111, //添加后的项目ID
			name : '项目名称'
		}
	}
	```