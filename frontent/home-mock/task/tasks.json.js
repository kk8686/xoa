module.exports = dataMocker.mock({
	message : '',
	code : 0,
	'data|1-7' : [{
		id : 11,
		title : /(开发|修复)[\u4E00-\u9FA5]{4,20}功能/,
		limit_time : '@DATETIME("2016-MM-dd HH:mm:ss")',
		'workers|1-3' : [{
			name : /(小明|小红|陈莹莹|王晶晶|张风|林云龙)/,
			avatar : /\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/
		}] 
	}]
});