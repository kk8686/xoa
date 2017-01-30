module.exports = dataMocker.mock({
	message : '',
	code : 0,
	data : {
		id : 11,
		title : '子任务标题',
		limit_time : '@DATETIME("2016-MM-dd HH:mm:ss")',
		worker : {
			name : /(小明|小红|陈莹莹|王晶晶|张风|林云龙)/,
			avatar : /\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/
		}
	}
});
