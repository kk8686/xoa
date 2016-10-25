<?php
namespace app\controllers;

use Yii;
use xoa\forms\LoginForm;
use xoa\ext\Response;

class UserController extends \yii\rest\ActiveController{
	public $modelClass = 'xoa\models\User';
	
    public function actionShowLogin(){
		return $this->renderPartial('login');
    }
	
	public function actionLogin(){
		$form = new LoginForm();
		if(!$form->load([
			'email' => (string)Yii::$app->request->post('h79843'),
			'password' => (string)Yii::$app->request->post('h79844'),
		], '')){
			return new Response('接收参数失败');
		}
		
		if(!$form->login()){
			return new Response(current($form->errors)[0]);
		}else{
			return new Response('登陆成功', 0, '/home.html');
		}
	}
	
	public function actionRegister(){
		
	}
}