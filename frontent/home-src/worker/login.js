require(['app', 'format-validator'], function(app, formatValidator){
	function login(){
		var email = $('#email').val(),
			password = $('#password').val().trim();
		if(!formatValidator.isEmail(email)){
			app.alert('请输入邮箱帐号');
			return;
		}

		if(password.length < 6 || password.length > 16){
			app.alert('密码格式不正确');
			return;
		}

		app.ajax({
			url : '/worker/login.do',
			data : {
				h79843 : email,
				h79844 : password
			},
			success : function(result){
				app.alert(result.message, result.code, result.data);
			}
		});
	}
	
	function initPage(){
		$('#container').html('<form style="width:300px; height:200px; margin:30px auto;">\
		<fieldset class="form-group">\
			<input class="form-control" id="email" type="text" placeholder="登陆邮箱" />\
		</fieldset>\
		<fieldset class="form-group">\
			<input class="form-control" id="password" type="password" placeholder="密码" />\
		</fieldset>\
		<fieldset class="form-group">\
			<button type="button" class="btn btn-primary J-btnLogin">登陆</button>\
			&nbsp;&nbsp;<a href="/worker/register.html">注册新账号</a>\
		</fieldset>\
	</form>').find('.J-btnLogin').click(login);
	}
	
	initPage();
});