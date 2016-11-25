var mocker = require('mockjs');
module.exports = mocker.mock({
	message : '',
	code : 0,
	'data|1-7' : [{
		id : 11,
		title : /(开发|修复)[\u4E00-\u9FA5]{4,7}功能/,
		limit_time : '@DATETIME("2016-MM-dd HH:mm:ss")',
		'workers_avatar|1-3' : [/\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/] 
	}]
});