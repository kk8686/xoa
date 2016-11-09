response.writeHead(301, {
	'X-Redirect' : '/worker/login.html'
});
response.end();