<?php
namespace xoa\home\controllers;

use xoa\common\ext\web\Response;
use xoa\home\models\Project;

class ProjectController extends \yii\web\Controller{
	public function actionList(){
		$list = Project::find()->asArray()->select(['id', 'name'])->all();
		return new Response('', 0, $list);
	}
}