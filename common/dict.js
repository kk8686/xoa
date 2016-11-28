module.exports = {
	commonModelsTask : {
		LEVEL_NORMAL : 3,
		REPEAT_NO : 1,
		REPEAT_WORK_DAY : 2,
		REPEAT_EVERY_DAY : 3,
		REPEAT_EVERY_WEEK : 4,
		REPEAT_EVERY_MONTH : 5,
		labels : {
			id : null,
			project_id : null,
			task_category_id : null,
			creater_id : null,
			worker_ids : null,
			related_member_ids : null,
			title : null,
			detail : null,
			level : null,
			repeat : null,
			is_finish : null,
			order : null,
			limit_time : null,
			history : null,
			end_time : null,
			add_time : null,
			repeats : {
				1 : "不重复",
				2 : "工作日",
				3 : "每天",
				4 : "每周",
				5 : "每月"
			},
			levels : {
				3 : "普通"
			}
		}
	},
	commonModelsProject : {
		labels : {
			id : null,
			name : null,
			worker_id : null,
			member_ids : null,
			add_time : null
		}
	},
	commonModelsWorker : {
		GENDER_MALE : 1,
		GENDER_FEMALE : 2,
		labels : {
			id : null,
			email : null,
			mobile : null,
			password_hash : null,
			hash_key : null,
			name : null,
			avatar : null,
			gender : null,
			birthday : null,
			add_time : null
		}
	}
};