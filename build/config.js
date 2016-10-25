var path = require('path');

module.exports = {
	projects : {
		1 : { //前台
			src : path.resolve('../frontent/src-home'),
			dist : path.resolve('../web/home'),
			mockData : path.resolve('../frontent/mock-home'),
			layouts : [
				{
					layoutFile : '',
					files : [
						'worker/register.html',
						'login.html'
					]
				}
			]
		},
		2 : { //后台
			src : path.resolve('../frontent/src-backend'),
			dist : path.resolve('../web/backend'),
			mockData : path.resolve('../frontent/mock-backend'),
			layouts : [
				{
					layoutFile : '',
					files : [
						'login.html'
					]
				}
			]
		}
	}
};