if(request.body.h79843 === 'root@soa.com' && request.body.h79844 === '121212'){
	module.exports = {
		message : '登陆成功',
		code : 0,
		data : '/home.html'
	};
}else{
	module.exports = {
		message : '帐号或密2码错误',
		code : 1,
		data : null
	};
}