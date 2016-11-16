工作者 - 登陆
===

- 地址：POST `/worker/login.do`

- 参数：

	- `h79843`：邮箱

	- `h79844`：密码，6-16位
	
- 返回：

	```js
	{
		message : '',
		code : 0,
		data : '/home.htm' //登陆成功后的跳转地址
	}
	```