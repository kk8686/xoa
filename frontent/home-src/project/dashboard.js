(function(){
function _fShowTaskForm(){
	var $dialog = new ModalDialog({
		title : '新任务',
		content: '<form role="form" class="wrapAddTaskWorker">\
			<div class="form-group">\
				<input type="text" class="form-control" placeholder="任务标题">\
			</div>\
			<div class="form-group">\
				<textarea class="form-control" rows="5" placeholder="任务详情，选填"></textarea>\
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
		</form>',
		width : 500,
		height : 450,
		footer : '<button type="button" class="btn btn-primary">确定</button>'
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
			var selectedIds = (function(){
				var ids = [];
				$wrapWorkerResult.find('.J-worker').each(function(){
					ids.push($(this).data('id'));
				});
				return ids;
			})();

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

			//已经选中的负责人ID
			var selectedIds = (function(){
				var ids = [];
				$wrapWorkerResult.find('.J-member').each(function(){
					ids.push($(this).data('id'));
				});
				return ids;
			})();

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

TaskCategory.prototype.taskList = function(){
	var self = this;
	
	return {
		addTask : function(task){
			self.append(task);
		}
	};
};

TaskCategory.prototype.showTasks = function(){
	var self = this;
	App.ajax({
		url : '/project/' + App.aParams.projectId + '/tasks.json',
		success : function(result){
			for(var i in result.data){
				var task = new Task(result.data[i]);
				console.log(self.taskList);
				self.taskList.addTask(task);
			}
		}
	});
};

function Task(){
	
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
				aCategoryList[category.id] = category;
				//category.showTasks();
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

var aCategoryList = {};

$(function(){
	App.loadParam('project/<projectId:\\d+>.htm');
	listenPage();
	showProjectInfo();
	showTasks();
});
})();