<?php
namespace xoa\home\controllers;

use Yii;
use xoa\common\ext\web\Response;

use xoa\common\models\Project;
use xoa\home\forms\ProjectForm;

/**
 * 项目控制器
 * @author KK
 */
class ProjectController extends \yii\web\Controller{
	
	/**
	 * 获取项目列表
	 * @author KK
	 * @return Response
	 */
	public function actionList(){
		$list = Project::find()->asArray()->select(['id', 'name'])->where(['worker_id' => Yii::$app->worker->id])->all();
		return new Response('', 0, $list);
	}
	
	/**
	 * 添加项目
	 * @author KK
	 * @return Response
	 */
	public function actionAdd(){
		$form = new ProjectForm(['scenario' => ProjectForm::SCENE_ADD]);
		if(!$form->load(Yii::$app->request->post(), '')){
			return new Response('缺少请求参数');
		}
		
		$form->creater = Yii::$app->worker;
		if($project = $form->add()){
			return new Response('添加成功', 0, $project->toArray(['id', 'name']));
		}else{
			return new Response(current($form->errors)[0]);
		}
	}
}