/**
 * 数据字典，本文件由服务端程序生成，请勿手动修改
 */
module.exports = {
	commonModelsTask : {
		LEVEL_WAIT : 1,
		LEVEL_NOT_URGENT : 2,
		LEVEL_NORMAL : 3,
		LEVEL_URGENT : 4,
		LEVEL_SO_URGENT : 5,
		REPEAT_NO : 1,
		REPEAT_WORK_DAY : 2,
		REPEAT_EVERY_DAY : 3,
		REPEAT_EVERY_WEEK : 4,
		REPEAT_EVERY_MONTH : 5,
		labels : {
			repeats : {
				1 : "不重复",
				2 : "工作日",
				3 : "每日",
				4 : "每周",
				5 : "每月"
			},
			levels : {
				1 : "有空处理",
				2 : "不急",
				3 : "普通",
				4 : "紧急",
				5 : "非常紧急"
			}
		}
	},
	commonModelsWorker : {
		GENDER_MALE : 1,
		GENDER_FEMALE : 2
	}
};