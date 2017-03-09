define(['app', 'modal-dialog'], function(app, modalDialog){
	/**
	 * 构建任务详情弹窗
	 * @param {object} aTaskDetail
	 * @returns {ModalDialog}
	 */
	function _build(aTaskDetail){
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

		var $dialog = modalDialog.build({
			width : 500,
			height : 520,
			title : '任务详情',
			dialogClass : 'wrapTaskDetail',
			content : '<form role="form">\
				<div class="form-group taskTitle J-taskTitle" contenteditable="true">' + aTaskDetail.title + '</div>\
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
							<p>' + self.dict.levels[aTaskDetail.level] + '</p>\n\
						</div>\n\
						<div class="col-md-3">\n\
							<p>重复</p>\n\
							<p>' + self.dict.repeats[aTaskDetail.repeat] + '</p>\n\
						</div>\n\
					</div>\n\
				</div>\
				<div class="form-group">\n\
					<textarea class="form-control J-detail" rows="4" placeholder="任务详情，支持用Markdown书写">' + aTaskDetail.detail + '</textarea>\
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

		var $title = $dialog.find('.J-taskTitle'),
			$detail = $dialog.find('.J-detail');

			$title.data('last_content', $title.text());
			$detail.data('last_content', $detail.val());
		//$($title.selector + ',' + $detail.selector).blur(function(){
		$dialog.find($title.selector + ',' + $detail.selector).blur(function(){
			var $this = $(this);
			var lastContent = $this.data('last_content'),
				isTitle = $this.hasClass('J-taskTitle'),
				value = isTitle ? $this.text() : $this.val();

			if(value == lastContent){
				//没改变
				return;
			}

			app.ajax({
				url : '/task/update.do',
				data : {
					taskId : aTaskDetail.id,
					title : $title.text(),
					detail : $detail.val()
				},
				success : function(aResult){
					if(aResult.code){
						alert(aResult.message);
						return;
					}
					$this.data('last_content', isTitle ? $this.text() : $this.val());
				}
			});
		});

		$dialog.find('.btnAddChildTask').click(function(){
			var $wrapAddChildTask = $(this).closest('.J-wrapAddChildTask');
			$wrapAddChildTask.hide();

			var $wrapForm = _buildChildTaskForm(aTaskDetail.workers[0], $wrapAddChildTask);
			$wrapAddChildTask.after($wrapForm);
		});

		return $dialog;
	}
	
	/**
	 * 构建添加子任务的表单
	 * @param {type} defaultWorker
	 * @param {type} $wrapAddChildTask
	 * @returns {jQuery|$|@pro;window@pro;$|Window.$}
	 */
	function _buildChildTaskForm(defaultWorker, $wrapAddChildTask){
		var $wrapForm = $('<div class="row wrapAddChildTaskForm">\n\
			<textarea class="form-control J-childTaskContent" rows="2"></textarea>\n\
			<p>\n\
				<span class="childTaskWorker J-childTaskWorker"><img class="avatar" src="' + defaultWorker.avatar + '" title="' + defaultWorker.name + '"/></span>\n\
				<button type="button" class="btn btn-success J-saveChildTask">保存</button>\n\
			</p>\n\
		</div>');

		$wrapForm.find('.J-saveChildTask').click(function(){
			var content = $wrapForm.find('.J-childTaskContent').val().trim();
			if(!content){
				alert('请先填写任务内容');
				return;
				
			}
			var worker = defaultWorker;
			app.ajax({
				url : '/task/add-child-task.do',
				data : {
					content : content,
					worker : worker
				},
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
		
		//选择子任务负责人
		var $workers = null;
		$wrapForm.find('.J-childTaskWorker').click(function(){
			var $this = $(this);
			if($workers){
				$workers.is(':hidden') ? $workers.slideDown(100) : $workers.slideUp(100);
				return;
			}
			
			app.ajax({
				url : '/project/' + app.aParams.projectId + '/members.json',
				success : function(result){
					var workersHtml = [];
					for(var i in result.data){
						var worker = result.data[i];
						workersHtml.push('<li class="J-childTaskWorkerItem" data-id="' + worker.id + '"><img class="avatar" src="' + worker.avatar + '" title="' + worker.name + '" /></li>');
					}
					
					
					$workers = $('<ul style="display:none;">' + workersHtml.join('') + '</ul>');
					
					$this.after($workers);
					
					var left = $this.offset().left - $this.parent().offset().left + 'px'; //对齐子任务责任人选择结果
					$workers.css({left : left});
					$workers.on('click', '.J-childTaskWorkerItem', function(){
						$this.data('id', $(this).data('id'));
						$workers.slideUp(100);
					});
					$workers.slideDown(100);
				}
			});
		});
		return $wrapForm;
	}


	var self = {
		/**
		 * 字典
		 */
		dict : {
			levels : {},
			repeats : {},
		},
		build : _build
	};
	return self;
});