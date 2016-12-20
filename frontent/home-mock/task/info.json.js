module.exports = dataMocker.mock({
	message : '',
	code : 0,
	data : {
		'id|1-999' : 1,
		title : /(开发|修复)[\u4E00-\u9FA5]{4,20}功能/,
		detail : /(开发|修复) [\u4E00-\u9FA5]{15,40} 功能/,
		'workers|1-3' : [{
			name : /(小明|小红|陈莹莹|王晶晶|张风|林云龙)/,
			avatar : /\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/
		}],
		'related_members|1-6' : [{
			name : /(小明|小红|陈莹莹|王晶晶|张风|林云龙)/,
			avatar : /\/data\/worker\/avatar\/xx\.jpg\?id=[1-5]/
		}],
		limit_time : '@DATETIME("2016-MM-dd HH:mm:ss")',
		'level|1-5' : 1,
		'repeat|1-5' : 1,
		'history' : [
			//任务历史事件，详细内容未确定，可以先不显示历史
			{
				id : 1,
				time : 1, //发生时间
				type : 1, //事件类型
				desc : 'KK 修改了任务内容为 $任务内容的前200字', //事件描述
			}
		],
	}
});
