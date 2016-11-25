var mocker = require('mockjs');
module.exports = mocker.mock({
	message : '',
	code : 0,
	'data|1-7' : [{
		id : 11,
		title : '开发功能A',
		limit_time : '@DATETIME("2016-MM-dd HH:mm:ss")',
		worker_avatar : /\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/
	}]
});