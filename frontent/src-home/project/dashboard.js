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
	
	$category.on('click', '.J-btnAddTask', function(){
		var $dialog = new ModalDialog({
			title : '新任务',
			content: '<form role="form" style="padding:10px;">\
				<div class="form-group">\
					<input type="email" class="form-control" placeholder="任务标题">\
				</div>\
				<div class="form-group">\
					<label>详情</label>\
					<textarea class="form-control" rows="5"></textarea>\
				</div>\
				<div class="form-group">\
					<button type="button" class="btn btn-success">+负责人</button>\
					<p class="help-block"></p>\
				</div>\
				<div class="checkbox">\
					<label>\
						<input type="checkbox">\
					</label>\
				</div>\
				\
			</form>',
			width : '500px',
			height : '400px',
			footer : '<button type="button" class="btn btn-primary">确定</button>'
		});
		
		$dialog.on('click', 'J-btnAddWorker', function(){
			var $this = $(this);
			var members = $this.data('members');
			if(!members){
				App.ajax({
					url : '/project/' + App.aParams.projectId + '/workers.json'
				});
			}
		});
		
		$dialog.show();
	});
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