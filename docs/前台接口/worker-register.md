工作者 - 注册
===

- 地址：POST `/worker/register.do`

- 参数：

	- `h79843`：邮箱

	- `h79844`：密码，6-16位
	
	这么命名是为了防止非https下容易被抓包监听账号密码（匹配username password之类的关键词）
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : null
	}
	```