define(['app', 'format-validator'], function(app, FormatValidator){
	/**
	 * 初始化页面基础DOM
	 */
	function _initPage(){
		$('#mainOut').html('<div id="wrapProjectHead" class="row wrapProjectName">\
			<label id="projectName"></label>\
			<button type="button" class="btn btn-success J-btnAddMember">+成员</button>\
		</div>\
		<div id="taskCategorys" class="row taskCategorys"></div>');
	}
	
	/**
	 * 监听页面事件
	 */
	function _listenPage(){
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
				app.alert('UID是一个数字 (⊙ｏ⊙) 麻烦叫他进个人信息里看看');
				return;
			}

			app.ajax({
				url : '/project/invite-member.do',
				data : {
					projectId : app.aParams.projectId,
					inviteWorkerId : workerId.trim()
				},
				success : function(aResult){
					app.alert(aResult.message);
				}
			});
		});
	}
	
	
	function _showProjectInfo(){
		app.ajax({
			url : '/project/' + app.aParams.projectId + '.json',
			success : function(result){
				$('#projectName').text(result.data.name);
			}
		});
	}

	function _showTasks(){
		app.ajax({
			url : '/project/' + app.aParams.projectId + '/task-categorys.json',
			success : function(aResult){
				require(['task-category'], function(taskCategory){
					var $taskCategorys = $('#taskCategorys');
					var aCategories = [];
					for(var i in aResult.data){
						var $category = taskCategory.build(aResult.data[i]);
						aCategories.push($category);
						$category.refreshTasks();
					}
					$taskCategorys.append(aCategories);
					taskCategory.aTaskCategorys = aCategories;
				});
			}
		});
	}
	
	return {
		dict : {},
		render : function(){
			app.loadParam('project/<projectId:\\d+>.htm');
			_initPage();
			_listenPage();
			_showProjectInfo();
			_showTasks();
		}
	};
});