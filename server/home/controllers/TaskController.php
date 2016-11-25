<?php
namespace xoa\home\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use xoa\common\ext\web\Response;
use xoa\home\forms\TaskForm;

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
		if($task = $form->add()){
			$taskInfo = $task->toArray(['id', 'title', 'limit_time']);
			$taskInfo['workers'] = ArrayHelper::filter($task->workers, ['id', 'name', 'avatar']);
			return new Response('', 0, $taskInfo);
		}else{
			return new Response(current($form->errors)[0]);
		}
	}
	
	/**
	 * 任务列表
	 * @author KK
	 * @param int $categoryId 任务分类ID
	 */
	public function actionList(int $categoryId) {
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
}
