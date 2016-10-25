var path = require('path');

module.exports = {
	projects : {
		1 : { //前台
			src : path.resolve('../frontent/src-frontent'),
			dist : path.resolve('../web/frontent'),
			mockData : path.resolve('../frontent/mock-frontent'),
			layouts : [
				{
					layoutFile : '',
					files : [
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