var path = require('path');
var PROJECT_PATH = path.resolve('../');
module.exports = {
	projects : {
		1 : { //前台
			src : PROJECT_PATH + '/frontent/src-home',
			dist : PROJECT_PATH + '/web/home',
			mockData : PROJECT_PATH + '/frontent/mock-home',
			layouts : [
				{
					layoutFile : '',
					files : [
						'worker/register.html',
						'login.html'
					]
				}
			],
			urlRulesFile : PROJECT_PATH + '/frontent/home-url-rules.js',
			buildUrlRulesFileName : PROJECT_PATH+ '/web/home-url-rules.json'
		},
		
		2 : { //后台
			src : PROJECT_PATH + '/frontent/src-backend',
			dist : PROJECT_PATH + '/web/backend',
			mockData : PROJECT_PATH + '/frontent/mock-backend',
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