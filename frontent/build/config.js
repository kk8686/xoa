var path = require('path');
var PROJECT_PATH = path.resolve('../../');
var dict = require(PROJECT_PATH + '/common/dict.js'); //数据字典，由后端生成

module.exports = {
	projects : {
		1 : { //前台
			src : PROJECT_PATH + '/frontent/home-src',
			dist : PROJECT_PATH + '/web/home',
			mockData : PROJECT_PATH + '/frontent/home-mock',
			layouts : [
				{
					layoutFile : '',
					files : [
						'worker/register.html',
						'worker/login.html'
					]
				}
			],
			dict : dict,
			urlRulesFile : PROJECT_PATH + '/frontent/home-url-rules.js',
			buildUrlRulesFileName : PROJECT_PATH+ '/web/home-url-rules.json'
		},
		
		2 : { //后台
			src : PROJECT_PATH + '/frontent/backend-src',
			dist : PROJECT_PATH + '/web/backend',
			mockData : PROJECT_PATH + '/frontent/backend-mock',
			layouts : [
				{
					layoutFile : '',
					files : [
						'login.html'
					]
				}
			],
			dict : dict
		}
	}
};