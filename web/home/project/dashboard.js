!function(){function t(t){var e=$(t.delegateTarget).data("id"),a=new Date,s=a.getFullYear()+"/"+a.getMonth()+"/"+a.getDate()+" 18:00",r=new ModalDialog({width:500,height:520,title:"新任务",content:'<form role="form" class="wrapAddTaskWorker">			<div class="form-group">				<input type="text" class="form-control J-taskTitle" placeholder="任务标题">			</div>			<div class="form-group">				<textarea class="form-control J-detail" rows="5" placeholder="任务详情，选填"></textarea>			</div>			<div class="form-group">				<button type="button" class="btn btn-success J-btnAddWorker">+负责人</button>				<span class="J-wrapWorkerResult"></span>			</div>			<div class="checkbox warpMembers J-wrapSelectWorkers"></div>			<div class="form-group">				<button type="button" class="btn btn-success J-btnAddRelatedMember">+相关人员</button>				<span class="J-wrapRelatedMembersResult"></span>			</div>			<div class="checkbox warpMembers J-wrapSelectRelationWorkers"></div>			<div class="form-group">				<label>完成时间：</label>\n				<input class="form-control J-limitTime" type="dateTime-local" value="'+s+'">			</div>		</form>',footer:'<button type="button" class="btn btn-primary J-submit">确定</button>'});r.__defineGetter__("selectedWorkerIds",function(){var t=[];return this.find(".J-wrapWorkerResult .J-worker").each(function(){t.push($(this).data("id"))}),t}),r.__defineGetter__("selectedRelatedMemberIds",function(){var t=[];return this.find(".J-wrapRelatedMembersResult .J-member").each(function(){t.push($(this).data("id"))}),t}),r.on("click",".J-btnAddWorker",function(){var t=$(this),e=r.find(".J-wrapWorkerResult"),a=r.find(".J-wrapSelectWorkers");if("+负责人"==t.text()){var s=r.data("members");s||App.ajax({url:"/project/"+App.aParams.projectId+"/members.json",async:!1,success:function(t){r.data("members",t.data),s=t.data}});var i=r.selectedWorkerIds,n=[];for(var o in s){var d=-1!==$.inArray(s[o].id,i)?" checked":"";n.push('<label class="J-memberItem"><input class="J-member" type="checkbox" value="'+s[o].id+'"'+d+">"+s[o].name+"</label>")}a.html(n.join("")),t.text("确定")}else{var n=[];a.find(":checkbox").each(function(){this.checked&&n.push('<label class="J-worker" data-id="'+this.value+'">'+$(this).closest(".J-memberItem").text()+"</label>")}),e.html(n.join("、")),a.empty(),t.text("+负责人")}}),r.on("click",".J-btnAddRelatedMember",function(){var t=$(this),e=r.find(".J-wrapRelatedMembersResult"),a=r.find(".J-wrapSelectRelationWorkers");if("+相关人员"==t.text()){var s=r.data("members");s||App.ajax({url:"/project/"+App.aParams.projectId+"/members.json",async:!1,success:function(t){r.data("members",t.data),s=t.data}});var i=r.selectedRelatedMemberIds,n=[];for(var o in s){var d=-1!==$.inArray(s[o].id,i)?" checked":"";n.push('<label class="J-memberItem"><input class="J-member" type="checkbox" value="'+s[o].id+'"'+d+">"+s[o].name+"</label>")}a.html(n.join("")),t.text("确定")}else{var n=[];a.find(":checkbox").each(function(){this.checked&&n.push('<label class="J-member" data-id="'+this.value+'">'+$(this).closest(".J-memberItem").text()+"</label>")}),e.html(n.join("、")),a.empty(),t.text("+相关人员")}}),r.find(".J-submit").click(function(){var t=r.find(".J-taskTitle").val().trim(),a=r.find(".J-detail").val().trim(),s=r.selectedWorkerIds,n=r.selectedRelatedMemberIds,d=r.find(".J-limitTime").val();return t?s.length?void App.ajax({url:"/task/add.do",data:{title:t,detail:a,taskCategoryId:e,workerIds:s,relatedMemberIds:n,limitTime:d},success:function(t){if(alert(t.message),!t.code){var a=new i(t.data),s=o.getTaskCategory(e);s.addTask(a),r.hide()}}}):alert("请选择负责人"):alert("请输入任务标题")}),r.show()}function e(e){var a=this;a.id=e.id,a.name=e.name;var s=$('<div class="col-md-4 taskList J-taskList" data-id="'+a.id+'">\n		<h4 class="J-taskCategoryName">\n			'+a.name+'			<button type="button" class="btn btn-primary J-btnAddTask">+任务</button>		</h4>\n		<div class="J-listItems">\n			\n		</div>	</div>');return s.find(".J-taskCategoryName").hover(function(){$(this).find(".J-btnAddTask").show()},function(){$(this).find(".J-btnAddTask").hide()}),s.on("click",".J-btnAddTask",t),s.refreshTasks=function(){var t=this.find(".J-listItems").empty();App.ajax({url:"/task/"+this.data("id")+"/tasks.json",success:function(e){for(var a in e.data){var s=new i(e.data[a]);o.aTasks[e.data[a].id]=s,s.css("opacity",0),t.append(s),s.animate({opacity:1},1e3)}}})},s.addTask=function(t){this.find(".J-listItems").append(t)},s.on("dragover",function(t){t.preventDefault();var e=$(t.target).hasClass("J-item");s.data("dont_append",e)}),s.on("drop",function(){s.data("dont_append")||n(o.$dragingTask,$(this).find(".J-listItems"),!0),s.data("dont_append",!1)}),s.on("click",".J-item",function(){var t=o.aTasks[$(this).data("id")];t.showDetail()}),$.extend(a,s)}function a(){var t=$("#wrapProjectHead"),e=t.find(".J-btnAddMember");t.hover(function(){e.show()},function(){e.hide()}),e.click(function(){var t=prompt("请输入他的UID，小x会发个加入邀请给他");if(t)return FormatValidator.isInteger(t)?void App.ajax({url:"/project/invite-member.do",data:{projectId:App.aParams.projectId,inviteWorkerId:t.trim()},success:function(t){App.alert(t.message)}}):void App.alert("UID是一个数字 (⊙ｏ⊙) 麻烦叫他进个人信息里看看")})}function s(){App.ajax({url:"/project/"+App.aParams.projectId+"/task-categorys.json",success:function(t){var a=$("#taskCategorys"),s=[];for(var r in t.data){var i=new e(t.data[r]);s.push(i),i.refreshTasks()}a.append(s),o.aTaskCategorys=s}})}function r(){App.ajax({url:"/project/"+App.aParams.projectId+".json",success:function(t){$("#projectName").text(t.data.name)}})}function i(t){var e=function(t){return t.substr(5).substr(0,11)+" 完成"},a=[];for(var s in t.workers){var r=t.workers[s];a.push('<img class="pull-right" src="'+r.avatar+'" title="'+r.name+'"/>')}var i='<div class="item J-item" draggable="true" data-id="'+t.id+'">		<p class="taskTitle"><input type="checkbox" class="J-chkFinish" />'+t.title+'</p>		<p>			<span class="pull-left">'+e(t.limit_time)+"</span>			"+a.join("")+"		</p>	</div>",d=$.extend(this,$(i));return d.on("dragstart",function(t){o.$dragingTask=$(this)}),d.on("dragover",function(t){t.preventDefault()}),d.on("drop",function(){n(o.$dragingTask,$(this))}),d.showDetail=function(){var e=new ModalDialog({width:500,height:520,title:"任务详情",content:'<form action="" role="form">				<div class="form-group J-title" contenteditable="true">'+t.title+'</div>				<div class="form-group">\n				<textarea class="form-control">'+t.detail+'</textarea>				</div>				<div class="form-group"></div>				<div class="form-group"></div>			</form>',footer:'<button class="btn btn-default">关闭</button>'});e.find(".J-title").blur(function(){App.ajax({url:"/task/update.do",data:{taskId:d.data("id"),title:$(this).text()},success:function(t){t.code&&alert(t.message)}})}),e.show()},d}function n(t,e,a){a?e.append(t):e.before(t);var s=t.data("id");App.ajax({url:"/task/move.do",data:{taskId:s,taskCategoryId:t.closest(".J-taskList").data("id"),order:t.index()+1},error:function(){alert("移动出错，请重新加载页面")}}),t.addClass("dou"),setTimeout(function(){t.removeClass("dou")},500),o.$dragingTask=null}var o={aTaskCategorys:[],aTasks:[],$dragingTask:null,getTaskCategory:function(t){for(var e in this.aTaskCategorys)if(this.aTaskCategorys[e].data("id")==t)return this.aTaskCategorys[e]}};$(function(){App.loadParam("project/<projectId:\\d+>.htm"),a(),r(),s()})}();