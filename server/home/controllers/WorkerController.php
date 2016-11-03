<?php
namespace xoa\home\controllers;

use Yii;
use xoa\home\forms\LoginForm;
use xoa\common\ext\web\Response;
use xoa\common\forms\RegisterForm;

class WorkerController extends \yii\web\Controller{

	/**
	 * 检查登陆
	 * @author KK
	 * @return Response|string
	 * 如果未登陆会返回带重定向的Response
	 * 如果已经登陆会在pathinfo后面加个l，比如/mine.htm会变成/mine.html进行页面显示
	 * 如果URL直接写本方法的pathinfo进行路由会返回已经登陆的提示信息
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function actionCheckLogin(){
		if(Yii::$app->worker->isGuest){
			if(Yii::$app->request->get('rememberThisPage', 1)){
				Yii::$app->worker->returnUrl = \yii\helpers\Url::to('');
			}
			return $this->redirect(Yii::$app->worker->loginUrl);
		}
		
		$pageFile = Yii::getAlias('@webPath/') . Yii::$app->request->pathInfo . 'l';
		if(!file_exists($pageFile)){
			throw new \yii\web\NotFoundHttpException('找不到这个页面');
		}
		return $this->renderFile($pageFile);
	}
	
	/**
	 * 登陆
	 * @author KK
	 * @return Response
	 */
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
	
	/**
	 * 注册
	 * @author KK
	 * @return Response
	 * @throws \yii\base\UserException
	 */
	public function actionRegister(){
		$form = new RegisterForm();
		if(!$form->load([
			'email' => (string)Yii::$app->request->post('h79843'),
			'password' => (string)Yii::$app->request->post('h79844'),
		], '')){
			return new Response('接收参数失败');
		}
		
		if(!$worker = $form->register()){
			return new Response(current($form->errors)[0]);
		}
		
		if(!Yii::$app->worker->login($worker)){
			throw new \yii\base\UserException('登陆失败，请联系管理员');
		}
		return new Response('登陆成功', 0);
	}
	
	public function actionHeadbar(){
		return new Response('', 0, Yii::$app->worker->identity->toArray(['id', 'name']));
	}
}