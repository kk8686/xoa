module.exports = dataMocker.mock({
	message : '',
	code : 0,
	'data|1-7' : [{
		'id|11-999' : 11,
		name : /(小明|小红|陈莹莹|王晶晶|张风|林云龙)/,
		avatar : /\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/
	}]
});