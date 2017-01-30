require(['jquery', 'app'], function($, app){
	function add(){
		var name = prompt('请输入项目名称').trim();
		if(!name){
			return;
		}
		
		app.ajax({
			url : '/project/add.do',
			data : {
				name : name
			},
			success : function(result){
				app.alert(result.message);
				if(!result.code){
					location.reload();
				}
			}
		});
	}
	
	function initPage(){
		$('#mainOut').html('<h3>我参加的项目</h3>\
		<ul id="projectList" class="projectList"></ul>');
		
		app.ajax({
			url : '/project/list.json',
			success : function(result){
				if(result.code){
					app.alert(result.message);
					return;
				}

				var projectListHtml = [];
				for(var i in result.data){
					var project = result.data[i];
					projectListHtml.push('<li><a href="/project/' + project.id + '.htm">' + project.name + '</a></li>');
				}
				projectListHtml.push('<li><a href="javascript:add();">+项目</a></li>');
				$('#projectList').html(projectListHtml.join(''));
			}
		});
	}
	
	initPage();
});