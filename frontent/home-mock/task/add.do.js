module.exports = dataMocker.mock({
	message : '添加成功',
	code : 0,
	data : {
		id : 11,
		title : request.body.title,
		limit_time : '@DATETIME("2016-MM-dd HH:mm:ss")',
		'workers|1-3' : [
			//负责人
			{
				name : /(小明|小红|陈莹莹|王晶晶|张风|林云龙)/,
				avatar : /\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/
			}
		]
	}
});