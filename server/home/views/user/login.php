<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;

$this->registerJsFile('/assets/js/format-validator.js');
xoa\assets\CommonAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<?php echo Html::csrfMetaTags(); ?>
<title>登陆</title>
<?php $this->head(); ?>
</head>
<body class="container">
<?php $this->beginBody(); ?>
<form style="width:300px; height:200px; margin:30px auto;">
	<fieldset class="form-group">
		<input class="form-control" id="email" type="text" placeholder="登陆邮箱" />
	</fieldset>
	<fieldset class="form-group">
		<input class="form-control" id="password" type="password" placeholder="密码" />
	</fieldset>
	<fieldset class="form-group">
		<button type="button" class="btn btn-primary" onclick="login()">登陆</button>
	</fieldset>
</form>
<?php $this->endBody(); ?>
<script type="text/javascript">
function login(){
	var email = $('#email').val(),
		password = $('#password').val().trim();
	if(!FormatValidator.isEmail(email)){
		App.alert('请输入邮箱帐号');
		return;
	}
	
	if(password.length < 6 || password.length > 16){
		App.alert('密码格式不正确');
		return;
	}
	
	App.ajax({
		url : '<?php echo \yii\helpers\Url::to(['user/login']); ?>',
		data : {
			h79843 : email,
			h79844 : password
		},
		success : function(result){
			App.alert(result.message, result.code, result.data);
		}
	});
}
</script>
</body>
</html>
<?php $this->endPage();
