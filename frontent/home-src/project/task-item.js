define(['app'], function(app){	
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
					app.ajax({
						url : '/project/' + app.aParams.projectId + '/members.json',
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
					app.ajax({
						url : '/project/' + app.aParams.projectId + '/members.json',
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

			app.ajax({
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

	function _build(aTask){
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

		var $task = $(taskHtml);

		require(['task-category'], function(taskCategory){
			//拖动任务
			$task.on('dragstart', function(event){
				taskCategory.$dragingTask = $(this);
			});
			$task.on('dragover', function(event){
				event.preventDefault();
			});
			$task.on('drop', function(){
				taskCategory.moveTask(taskCategory.$dragingTask, $(this));
			});
		});

		//显示详情
		$task.showDetail = function(){
			var self = this;
			require(['task-detail', 'dashboard'], function(taskDetail, dashboard) {
				var aTaskDetail = self.data('detail');

				if(aTaskDetail){
					var $dialog = taskDetail.build(aTaskDetail);
					$dialog.show();
					return;
				}

				app.ajax({
					url : '/task/' + self.data('id') + '.json',
					success : function(aResult){
						if(aResult.code){
							app.alert(aResult.message);
							return;
						}

						self.data('detail', aResult.data);

						if(app.util.isEmptyObject(taskDetail.dict.levels)){
							taskDetail.dict.levels = dashboard.dict.levels;
							taskDetail.dict.repeats = dashboard.dict.repeats;
						}
						var $dialog = taskDetail.build(aResult.data);
						$dialog.show();
					}
				});
			});
		};

		return $task;
	}
	
	return {
		build : _build
	};
});