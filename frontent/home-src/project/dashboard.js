(function(container, $){
function _fShowTaskForm(event){
	var categoryId = $(event.delegateTarget).data('id');
	
	var today = new Date(),
		todayStr = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate() + ' 18:00';
	var $dialog = new ModalDialog({
		width : 500,
		height : 520,
		title : '新任务',
		content: '<form role="form" class="wrapAddTaskWorker">\
			<div class="form-group">\
				<input type="text" class="form-control J-taskTitle" placeholder="任务标题">\
			</div>\
			<div class="form-group">\
				<textarea class="form-control J-detail" rows="5" placeholder="任务详情，选填"></textarea>\
			</div>\
			<div class="form-group">\
				<button type="button" class="btn btn-success J-btnAddWorker">+负责人</button>\
				<span class="J-wrapWorkerResult"></span>\
			</div>\
			<div class="checkbox warpMembers J-wrapSelectWorkers"></div>\
			<div class="form-group">\
				<button type="button" class="btn btn-success J-btnAddRelatedMember">+相关人员</button>\
				<span class="J-wrapRelatedMembersResult"></span>\
			</div>\
			<div class="checkbox warpMembers J-wrapSelectRelationWorkers"></div>\
			<div class="form-group">\
				<label>完成时间：</label>\n\
				<input class="form-control J-limitTime" type="dateTime-local" value="' + todayStr + '">\
			</div>\
		</form>',
		footer : '<button type="button" class="btn btn-primary J-submit">确定</button>'
	});
	
	$dialog.__defineGetter__('selectedWorkerIds', function(){
		var ids = [];
		this.find('.J-wrapWorkerResult .J-worker').each(function(){
			ids.push($(this).data('id'));
		});
		return ids;
	});
	
	$dialog.__defineGetter__('selectedRelatedMemberIds', function(){
		var ids = [];
		this.find('.J-wrapRelatedMembersResult .J-member').each(function(){
			ids.push($(this).data('id'));
		});
		return ids;
	});

	//添加负责人
	$dialog.on('click', '.J-btnAddWorker', function(){
		var $this = $(this);
		var $wrapWorkerResult = $dialog.find('.J-wrapWorkerResult'),
			$wrapMembers = $dialog.find('.J-wrapSelectWorkers');
		if($this.text() == '+负责人'){
			//显示成员选项
			var members = $dialog.data('members');
			if(!members){
				App.ajax({
					url : '/project/' + App.aParams.projectId + '/members.json',
					async : false,
					success : function(aResult){
						$dialog.data('members', aResult.data);
						members = aResult.data;
					}
				});
			}

			//已经选中的负责人ID
			var selectedIds = $dialog.selectedWorkerIds;

			var membersHtml = [];
			for(var i in members){
				var checked = $.inArray(members[i].id, selectedIds) !== -1 ? ' checked' : '';
				membersHtml.push('<label class="J-memberItem"><input class="J-member" type="checkbox" value="' + members[i].id + '"' + checked + '>' + members[i].name + '</label>');
			};
			$wrapMembers.html(membersHtml.join(''));

			$this.text('确定');

		}else{
			//确定负责人成员
			var membersHtml = [];
			$wrapMembers.find(':checkbox').each(function(){
				if(this.checked){
					membersHtml.push('<label class="J-worker" data-id="' + this.value + '">' + $(this).closest('.J-memberItem').text() + '</label>');
				}
			});
			$wrapWorkerResult.html(membersHtml.join('、'));
			$wrapMembers.empty();
			$this.text('+负责人');
		}
	});

	//添加相关人员
	$dialog.on('click', '.J-btnAddRelatedMember', function(){
		var $this = $(this);
		var $wrapWorkerResult = $dialog.find('.J-wrapRelatedMembersResult'),
			$wrapMembers = $dialog.find('.J-wrapSelectRelationWorkers');
		if($this.text() == '+相关人员'){
			//显示成员选项
			var members = $dialog.data('members');
			if(!members){
				App.ajax({
					url : '/project/' + App.aParams.projectId + '/members.json',
					async : false,
					success : function(aResult){
						$dialog.data('members', aResult.data);
						members = aResult.data;
					}
				});
			}

			//已经选中的相关人员ID
			var selectedIds = $dialog.selectedRelatedMemberIds;

			var membersHtml = [];
			for(var i in members){
				var checked = $.inArray(members[i].id, selectedIds) !== -1 ? ' checked' : '';
				membersHtml.push('<label class="J-memberItem"><input class="J-member" type="checkbox" value="' + members[i].id + '"' + checked + '>' + members[i].name + '</label>');
			};
			$wrapMembers.html(membersHtml.join(''));

			$this.text('确定');

		}else{
			//确定负责人成员
			var membersHtml = [];
			$wrapMembers.find(':checkbox').each(function(){
				if(this.checked){
					membersHtml.push('<label class="J-member" data-id="' + this.value + '">' + $(this).closest('.J-memberItem').text() + '</label>');
				}
			});
			$wrapWorkerResult.html(membersHtml.join('、'));
			$wrapMembers.empty();
			$this.text('+相关人员');
		}
	});
	
	//提交新任务
	$dialog.find('.J-submit').click(function(){
		var title = $dialog.find('.J-taskTitle').val().trim(),
			detail = $dialog.find('.J-detail').val().trim(),
			aWorkerIds = $dialog.selectedWorkerIds,
			aRelatedMemberIds = $dialog.selectedRelatedMemberIds,
			limitTime = $dialog.find('.J-limitTime').val();
		
		if(!title){
			return alert('请输入任务标题');
		}
		if(!aWorkerIds.length){
			return alert('请选择负责人');
		}
		
		App.ajax({
			url : '/task/add.do',
			data : {
				title : title,
				detail : detail,
				taskCategoryId : categoryId,
				workerIds : aWorkerIds,
				relatedMemberIds : aRelatedMemberIds,
				limitTime : limitTime
			},
			success : function(aResult){
				alert(aResult.message);
				if(aResult.code){
					return;
				}
				var $task = new Task(aResult.data);
				var category = Page.getTaskCategory(categoryId);
				category.addTask($task);
				$dialog.hide();
			}
		});
	});

	$dialog.show();
}

/**
 * 任务分类
 * @param {object} options 分类信息，包含id和name
 * @returns {Object} 分类的DOM实例
 */
function TaskCategory(options){
	var self = this;
	self.id = options.id;
	self.name = options.name;
	
	var $category = $('<div class="col-md-4 taskList J-taskList" data-id="' + self.id + '">\n\
		<h4 class="J-taskCategoryName">\n\
			' + self.name + '\
			<button type="button" class="btn btn-primary J-btnAddTask">+任务</button>\
		</h4>\n\
		<div class="J-listItems">\n\
			\n\
		</div>\
	</div>');
	
	$category.find('.J-taskCategoryName').hover(function(){
		$(this).find('.J-btnAddTask').show();
	}, function(){
		$(this).find('.J-btnAddTask').hide();
	});
	
	//弹出添加任务对话框
	$category.on('click', '.J-btnAddTask', _fShowTaskForm);
	
	//刷新任务列表
	$category.refreshTasks = function(){
		var $itemContainer = this.find('.J-listItems').empty();
		App.ajax({
			url : '/task/' + this.data('id') + '/tasks.json',
			success : function(aResult){
				for(var i in aResult.data){
					var $task = new Task(aResult.data[i]);
					Page.aTasks[aResult.data[i].id] = $task;
					$task.css('opacity', 0);
					$itemContainer.append($task);
					$task.animate({opacity:1}, 1000);
				}
			}
		});
	};
	
	//添加新任务
	$category.addTask = function($task){
		this.find('.J-listItems').append($task);
	};
	
	//监听拖动任务
	$category.on('dragover', function(event){
		event.preventDefault();
		var dontAppend = $(event.target).hasClass('J-item');
		$category.data('dont_append', dontAppend);
	});
	$category.on('drop', function(){
		if(!$category.data('dont_append')){
			moveTask(Page.$dragingTask, $(this).find('.J-listItems'), true);
		}
		$category.data('dont_append', false);
	});
	
	//任务详情
	$category.on('click', '.J-item', function(){
		var $task = Page.aTasks[$(this).data('id')];
		$task.showDetail();
	});
	
	return $.extend(self, $category);
}

/**
 * 监听页面事件
 * @returns {undefined}
 */
function listenPage(){
	var $wrapProjectHead = $('#wrapProjectHead');
	var $btnAddMember = $wrapProjectHead.find('.J-btnAddMember');
	$wrapProjectHead.hover(function(){
		$btnAddMember.show();
	}, function(){
		$btnAddMember.hide();
	});
	
	$btnAddMember.click(function(){
		var workerId = prompt('请输入他的UID，小x会发个加入邀请给他');
		if(!workerId){
			return;
		}
		if(!FormatValidator.isInteger(workerId)){
			App.alert('UID是一个数字 (⊙ｏ⊙) 麻烦叫他进个人信息里看看');
			return;
		}
		
		App.ajax({
			url : '/project/invite-member.do',
			data : {
				projectId : App.aParams.projectId,
				inviteWorkerId : workerId.trim()
			},
			success : function(aResult){
				App.alert(aResult.message);
			}
		});
	});
}

function showTasks(){
	App.ajax({
		url : '/project/' + App.aParams.projectId + '/task-categorys.json',
		success : function(aResult){
			var $taskCategorys = $('#taskCategorys');
			var aCategories = [];
			for(var i in aResult.data){
				var category = new TaskCategory(aResult.data[i]);
				aCategories.push(category);
				category.refreshTasks();
			}
			$taskCategorys.append(aCategories);
			Page.aTaskCategorys = aCategories;
		}
	});
}

function showProjectInfo(){
	App.ajax({
		url : '/project/' + App.aParams.projectId + '.json',
		success : function(result){
			$('#projectName').text(result.data.name);
		}
	});
}

function Task(aTask){
	var fConvertTime = function(time){
		return time.substr(5).substr(0, 11) + ' 完成';
	};
	
	var avatarsHtml = [];
	for(var i in aTask.workers){
		var worker = aTask.workers[i];
		avatarsHtml.push('<img class="pull-right avatar" src="' + worker.avatar + '" title="' + worker.name + '"/>');
	}
	
	var taskHtml = '<div class="item J-item" draggable="true" data-id="' + aTask.id + '">\
		<p class="taskTitle"><input type="checkbox" class="J-chkFinish" />' + aTask.title + '</p>\
		<p>\
			<span class="pull-left">' + fConvertTime(aTask.limit_time) + '</span>\
			' + avatarsHtml.join('') + '\
		</p>\
	</div>';
	
	var $task = $.extend(this, $(taskHtml));
	
	//拖动任务
	$task.on('dragstart', function(event){
		Page.$dragingTask = $(this);
	});
	$task.on('dragover', function(event){
		event.preventDefault();
	});
	$task.on('drop', function(){
		moveTask(Page.$dragingTask, $(this));
	});
	
	//显示详情
	$task.showDetail = function(){
		var aTaskDetail = this.data('detail');
		if(aTaskDetail){
			var $dialog = buildTaskDetailDialog(aTaskDetail);
			$dialog.show();
			return;
		}
		
		var self = this;
		App.ajax({
			url : '/task/detail/' + this.data('id') + '.json',
			success : function(aResult){
				if(aResult.code){
					App.alert(aResult.message);
					return;
				}
				
				self.data('detail', aResult.data);
				
				var $dialog = buildTaskDetailDialog(aResult.data);
				$dialog.show();
			}
		});
	};
	
	return $task;
}

/**
 * 构建添加子任务的表单
 * @param {type} defaultWorker
 * @param {type} $wrapAddChildTask
 * @returns {jQuery|$|@pro;window@pro;$|Window.$}
 */
function buildChildTaskForm(defaultWorker, $wrapAddChildTask){
	var $wrapForm = $('<div class="row wrapAddChildTaskForm">\n\
		<textarea class="form-control J-content" rows="2"></textarea>\n\
		<p>\n\
			<span class="limitTime J-childTaskLimitTime">期限</span>\n\
			<img class="avatar J-childTaskWorker" src="' + defaultWorker.avatar + '" title="' + defaultWorker.name + '"/>\n\
			<span class="close J-close"></span>\n\
			<button type="button" class="btn btn-success J-saveChildTask">保存</button>\n\
		</p>\n\
	</div>');

	$wrapForm.find('.J-saveChildTask').click(function(){
		var content = $wrapForm.find('.J-content').val();
		var worker = defaultWorker;
		App.ajax({
			url : '/task/add-child-task.do',
			data : {},
			success : function(aResult){
				if(aResult.code){
					alert(aResult.message);
					return;
				}
				$wrapForm.remove();
				$wrapAddChildTask.show();
				$wrapAddChildTask.before('<div class="row childTask">\n\
					<div class="pull-left childContent">' + content + '</div>\n\
					<div class="pull-right childInfo">\n\
						明天 <img class="avatar avatarSmall" src="' + worker.avatar + '"/>\n\
					</div>\n\
				</div>');
			}
		});
	});
	return $wrapForm;
}

/**
 * 构建任务详情弹窗
 * @param {type} aTaskDetail
 * @returns {ModalDialog}
 */
function buildTaskDetailDialog(aTaskDetail){
	var workersHtml = (function(){
		var workersHtml = [];
		for(var i in aTaskDetail.workers){
			var worker = aTaskDetail.workers[i];
			workersHtml.push('<img class="avatar avatarSmall" src="' + worker.avatar + '" title="' + worker.name + '"/>');
		}
		return workersHtml.join('');
	})();
	
	var relatedMembersHtml = (function(){
		var relatedMembersHtml = [];
		for(var i in aTaskDetail.related_members){
			var member = aTaskDetail.related_members[i];
			relatedMembersHtml.push('<img class="avatar avatarSmall" src="' + member.avatar + '" title="' + member.name + '"/>');
		}
		return relatedMembersHtml.join('');
	})();

	var $dialog = new ModalDialog({
		width : 500,
		height : 520,
		title : '任务详情',
		dialogClass : 'wrapTaskDetail',
		content : '<form role="form">\
			<div class="form-group taskTitle J-title" contenteditable="true">' + aTaskDetail.title + '</div>\
			<div class="form-group">\n\
				<div class="row topInfo">\n\
					<div class="col-md-3">\n\
						<p>负责人</p>\n\
						<p>' + workersHtml + '</p>\n\
					</div>\n\
					<div class="col-md-3">\n\
						<p>截止时间</p>\n\
						<p>' + aTaskDetail.limit_time + '</p>\n\
					</div>\n\
					<div class="col-md-3">\n\
						<p>优先级</p>\n\
						<p>' + Page.aVars.levels[aTaskDetail.level] + '</p>\n\
					</div>\n\
					<div class="col-md-3">\n\
						<p>重复</p>\n\
						<p>' + Page.aVars.repeats[aTaskDetail.repeat] + '</p>\n\
					</div>\n\
				</div>\n\
			</div>\
			<div class="form-group">\n\
				<textarea class="form-control" rows="4" placeholder="任务详情，支持用Markdown书写">' + aTaskDetail.detail + '</textarea>\
			</div>\
			<div class="form-group">\n\
				<div class="row J-wrapAddChildTask">\n\
					<button type="button" class="btn btn-warning btnAddChildTask">+子任务</button>\
				</div>\
			</div>\
			<div class="form-group">\n\
				<div class="row">相关人员：' + relatedMembersHtml + '</div>\
			</div>\
		</form>',
		footer : '<button data-dismiss="modal" class="btn btn-default">关闭</button>'
	});

	$dialog.find('.J-title').blur(function(){
		App.ajax({
			url : '/task/update.do',
			data : {
				taskId : aTaskDetail.id,
				title : aTaskDetail.title
			},
			success : function(aResult){
				if(aResult.code){
					alert(aResult.message);
				}
			}
		});
	});
	
	$dialog.find('.btnAddChildTask').click(function(){
		var $wrapAddChildTask = $(this).closest('.J-wrapAddChildTask');
		$wrapAddChildTask.hide();
		
		var $wrapForm = buildChildTaskForm(aTaskDetail.workers[0], $wrapAddChildTask);
		$wrapAddChildTask.after($wrapForm);
	});
	
	return $dialog;
}

function moveTask($task, $context, isCategoryContext){
	if(isCategoryContext){
		$context.append($task);
	}else{
		$context.before($task);
	}
	
	var taskId = $task.data('id');
	App.ajax({
		url : '/task/move.do',
		data : {
			taskId : taskId,
			taskCategoryId : $task.closest('.J-taskList').data('id'),
			order : $task.index() + 1
		},
		success : function(aResult){
			if(aResult.code){
				App.alert(aResult.message);
			}
		},
		error : function(){
			App.alert('移动出错，请重新加载页面');
		}
	});
	
	$task.addClass('dou');
	setTimeout(function(){
		$task.removeClass('dou');
	}, 500);
	
	Page.$dragingTask = null;
}

container.Page = {
	aTaskCategorys : [],
	aTasks : [],
	$dragingTask : null,
	getTaskCategory : function(id){
		for(var i in this.aTaskCategorys){
			if(this.aTaskCategorys[i].data('id') == id){
				return this.aTaskCategorys[i];
			}
		}
	},
	aVars : {},
	render : function(){
		App.loadParam('project/<projectId:\\d+>.htm');
		listenPage();
		showProjectInfo();
		showTasks();
	}
};

})(window, jQuery);