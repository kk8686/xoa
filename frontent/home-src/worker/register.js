require(['app', 'format-validator'], function(app, formatValidator){
	function register(){
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
			url : '/worker/register.do',
			data : {
				h79843 : email,
				h79844 : password
			},
			success : function(aResult){
				app.alert(aResult.message, aResult.code, aResult.data);
				if(!aResult.code){
					location.href = '/home.htm';
				}
			}
		});
	}
	
	function initPage(){
		$('#container').html('<form style="width:300px; height:200px; margin:30px auto;">\
	<fieldset class="form-group">\
		<input class="form-control" id="email" type="text" placeholder="邮箱" />\
	</fieldset>\
	<fieldset class="form-group">\
		<input class="form-control" id="password" type="password" placeholder="密码" />\
	</fieldset>\
	<fieldset class="form-group">\
		<button type="button" class="btn btn-primary J-btnRegister">注册</button>\
		&nbsp;&nbsp;<a href="/worker/login.html">返回登陆</a>\
	</fieldset>\
</form>').find('.J-btnRegister').click(register);
	}
	
	initPage();
});