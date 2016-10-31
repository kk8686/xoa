function TaskCategory(options){
	var self = this;
	self.id = options.id;
	self.name = options.name;
	
	var $category = $('<div class="taskList J-taskList" data-id="' + self.id + '">\n\
		<h4>' + self.name + '</h4>\
	</div>');
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

var categoryList = {};

$(function(){
	App.loadParam('project/<projectId:\\d+>.htm');
	
	App.ajax({
		url : '/project/' + App.aParams.projectId + '.json',
		success : function(result){
			$('#projectName').text(result.data.name);
		}
	});
	
	App.ajax({
		url : '/project/' + App.aParams.projectId + '/task-categorys.json',
		success : function(result){
			if(result.code){
				App.alert(result.message, result.code, result.data);
				return;
			}
			
			var $taskCategorys = $('#taskCategorys');
			for(var i in result.data){
				var category = new TaskCategory(result.data[i]);
				$taskCategorys.append(category);
				categoryList[category.id] = category;
				category.showTasks();
			}
		}
	});
});