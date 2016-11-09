<?php
namespace xoa\home\controllers;

use Yii;
use xoa\common\ext\web\Response;
use xoa\common\models\Project;
use xoa\home\forms\ProjectForm;

/**
 * 项目控制器
 * @author KK
 * @property-read array $taskCategories 任务分类
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
	 * 获取项目信息
	 * @author KK
	 * @return Response
	 */
	public function actionInfo(){
		$id = (int)Yii::$app->request->get('projectId');
		if(!$project = Project::findOne($id)){
			return new Response('无效的项目ID');
		}
		
		return new Response('', 0, $project->toArray(['id', 'name']));
	}
	
	/**
	 * 获取任务分类
	 * @author KK
	 * @return Response
	 */
	public function actionTaskCategories(){
		$id = (int)Yii::$app->request->get('projectId');
		if(!$project = Project::findOne($id)){
			return new Response('无效的项目ID' . $id);
		}
		
		return new Response('', 0, $project->taskCategories);
	}
	
	/**
	 * 获取项目的成员
	 * @author KK
	 * @return Response
	 */
	public function actionWorkers(){
		$id = (int)Yii::$app->request->get('projectId');
		if(!$project = Project::findOne($id)){
			return new Response('无效的项目ID' . $id);
		}
		return new Response('', 0, $project->workers);
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
	
	/**
	 * 邀请新的项目成员
	 * @author KK
	 * @return Response
	 */
	public function actionInviteMember(){
		$form = new ProjectForm([
			'scenario' => ProjectForm::SCENE_INVITE_MEMBER,
		]);
		if(!$form->load(Yii::$app->request->post(), '')){
			return new Response('缺少请求参数');
		}
		if($form->inviteMember()){
			$message = '小x已经帮你向 ' . $form->worker->name . ' 发送了邀请咯 o(≧v≦)o~~赶脚团队又要变得强大一点了';
			return new Response($message, 0);
		}else{
			return new Response(current($form->errors)[0]);
		}
	}
}