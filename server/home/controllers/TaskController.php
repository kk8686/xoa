<?php
namespace xoa\home\controllers;

use Yii;
use xoa\common\ext\web\Response;
use xoa\home\forms\TaskForm;
use xoa\common\models\{
	Project
};

/**
 * 任务相关的控制器
 * @author KK
 */
class TaskController extends \yii\web\Controller{
	/**
	 * 添加任务
	 * @author KK
	 * @return Response
	 */
	public function actionAdd() : Response{
		$form = new TaskForm(['scenario' => TaskForm::SCENE_ADD]);
		if(!$form->load(Yii::$app->request->post(), '')){
			return new Response('缺少请求参数');
		}
		
		$form->worker = Yii::$app->worker;
		if($taskInfo = $form->add()){
			return new Response('添加成功', 0, $taskInfo);
		}else{
			return new Response(current($form->errors)[0]);
		}
	}
	
	/**
	 * 任务列表
	 * @author KK
	 * @param int $categoryId 任务分类ID
	 */
	public function actionList(int $categoryId) : Response{
		$form = new TaskForm([
			'scenario' => TaskForm::SCENE_LIST,
			'taskCategoryId' => $categoryId,
		]);
		$tasks = $form->getList();
		if($tasks === false){
			return new Response(current($form->errors)[0]);
		}else{
			return new Response('0', 0, $tasks);
		}
	}
	
	/**
	 * 任务列表
	 * @author KK
	 * @param int $taskId 任务ID
	 */
	public function actionInfo(int $taskId) : Response{
		$form = new TaskForm([
			'scenario' => TaskForm::SCENE_INFO,
			'taskId' => $taskId,
		]);
		$task = $form->getInfo();
		if($task === false){
			return new Response(current($form->errors)[0]);
		}else{
			return new Response('0', 0, $task);
		}
	}
	
	/**
	 * 移动任务到指定分类
	 * @author KK
	 * @return Response
	 */
	public function actionMove() : Response{
		$form = new TaskForm(['scenario' => TaskForm::SCENE_MOVE]);
		if(!$form->load(Yii::$app->request->post(), '')){
			return new Response('缺少请求参数');
		}
		if($task = $form->moveTask()){
			//Yii::$app->notice->send(Notice::TYPE_MOVE_TASK, $task); //通知的设计
			return new Response('', 0);
		}else{
			return new Response($form->firstError[0]);
		}
	}
	
	/**
	 * 获取任务分类
	 * @author KK
	 * @return Response
	 */
	public function actionTaskCategories() : Response{
		$id = (int)Yii::$app->request->get('projectId');
		if(!$project = Project::findOne($id)){
			return new Response('无效的项目ID' . $id);
		}
		
		return new Response('', 0, $project->taskCategories);
	}
}
