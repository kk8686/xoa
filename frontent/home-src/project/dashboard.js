(function(){
function _fShowTaskForm(){
	var today = new Date(),
		todayStr = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate() + ' 18:00';
	var $dialog = new ModalDialog({
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
		width : 500,
		height : 520,
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
				workerIds : aWorkerIds,
				relatedMemberIds : aRelatedMemberIds,
				limitTime : limitTime,
			},
			success : function(aResult){
				alert('添加成功');
				var $task = new Task(aResult.data);
			}
		});
	});

	$dialog.show();
}

function TaskCategory(options){
	var self = this;
	self.id = options.id;
	self.name = options.name;
	
	var $category = $('<div class="col-md-4 taskList J-taskList" data-id="' + self.id + '">\n\
		<h4 class="J-taskCategoryName">\n\
			' + self.name + '\
			<button type="button" class="btn btn-primary J-btnAddTask">+任务</button>\
		</h4>\
	</div>');
	
	$category.find('.J-taskCategoryName').hover(function(){
		$(this).find('.J-btnAddTask').show();
	}, function(){
		$(this).find('.J-btnAddTask').hide();
	});
	
	$category.on('click', '.J-btnAddTask', _fShowTaskForm);
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
			for(var i in aResult.data){
				var category = new TaskCategory(aResult.data[i]);
				$taskCategorys.append(category);
			}
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
	var taskHtml = 'TODO';
}

$(function(){
	App.loadParam('project/<projectId:\\d+>.htm');
	listenPage();
	showProjectInfo();
	showTasks();
});

})();