var mocker = require('mockjs');
module.exports = mocker.mock({
	message : '',
	code : 0,
	data : {
		id : 11,
		title : request.body.title,
		limit_time : '@DATETIME("2016-MM-dd HH:mm:ss")',
		'workers|1-3' : [
			//负责人
			{
				'id|1-999' : 11,
				name : '@cfirst@cname',
				avatar : /\/data\/worker\/avatar\/[1-5]\.jpg/
			}
		]
	}
});