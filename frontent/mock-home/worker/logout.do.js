response.writeHead(301, {
	//'X-Redirect' : '/worker/login.html'
	'X-Redirect' : 'http://www.baidu.com'
});
response.end();