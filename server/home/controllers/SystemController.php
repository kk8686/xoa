<?php
namespace xoa\home\controllers;

use Yii;
use xoa\common\ext\web\Response;
use xoa\home\forms\SystemForm;

/**
 * 系统控制器
 * @author KK
 */
class SystemController extends \yii\web\Controller{
	/**
	 * 检查是否有通知
	 * @author KK
	 * @return Response
	 */
	public function actionCheckNotice(){
		$form = new SystemForm([
			'scenario' => SystemForm::SCENE_CHECK_NOTICE,
			'worker' => Yii::$app->worker,
		]);
		return new Response('', 0, $form->checkNotice());
	}
}