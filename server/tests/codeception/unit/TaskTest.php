<?php
namespace xoa_test\home\unit;

use xoa\common\models\{
	Project,
	Task,
	TaskCategory,
	Worker
};
use xoa\home\forms\TaskForm;
use yii\base\InvalidParamException;

/**
 * 任务测试
 * @author KK
 */
class TaskTest extends \Codeception\TestCase\Test
{
    /**
     * @var \xoa_test\UnitTester
     */
    protected $tester;

	/**
	 * 测试添加任务
	 * @author KK
	 */
    public function testAdd(){
		$form = new TaskForm(['scenario' => TaskForm::SCENE_ADD]);
		$this->assertFalse($form->validate(), '什么都不填，肯定验证不过');
		$this->tester->assertHasKeys(['title', 'workerIds'], $form->errors);
		
		$form->detail = 'ab';
		$form->relatedMemberIds = 'ab';
		$this->assertFalse($form->validate(), '内容不对');
		$this->tester->assertHasKeys(['title', 'detail', 'workerIds', 'relatedMemberIds'], $form->errors);
		
		$form->workerIds = [8888];
		$form->relatedMemberIds = [9999];
		$this->assertFalse($form->validate());
		$this->assertArrayHasKey('relatedMemberIds', $form->errors);
		
		$form->load([
			'title' => '测试任务',
			'detail' => '',
			'taskCategoryId' => 1,
			'workerIds' => [2],
			'relatedMemberIds' => [3],
			'limitTime' => date('Y-m-d H:i', strtotime('+1day')),
		], '');
		$form->worker = Worker::findOne(1);
		$taskInfo = $form->add();
		$this->assertInternalType('array', $taskInfo, '添加成功后会返回任务信息');
		$this->tester->assertHasKeys(['id', 'title', 'limit_time', 'workers'], $taskInfo);
    }
	
	/**
	 * 测试任务分类所属的项目
	 * @author KK
	 */
	public function testTaskCategory(){
		$taskCategory = TaskCategory::findOne(1);
		$this->assertInstanceOf(Project::className(), $taskCategory->project);
		$this->assertEquals(1, $taskCategory->project->id);
	}
	
	/**
	 * 测试任务列表
	 * @author KK
	 */
	public function testList(){
		$form = new TaskForm(['scenario' => TaskForm::SCENE_LIST]);
		$this->assertFalse($form->getList(), '什么都不填，必有错');
		$this->tester->assertHasKeys(['taskCategoryId'], $form->errors, true, '任务分类ID相关的错误');
		
		$form->taskCategoryId = 1;
		$tasks = $form->getList();
		$this->assertInternalType('array', $tasks, '参数对了，会给出列表');
		$this->tester->assertListHasKeys(['id', 'title', 'limit_time', 'workers'], $tasks, true, '参数对了，会给出列表');
	}
	
	/**
	 * 测试允许修改的判断
	 * @author KK
	 */
	public function testIsAllowModify() {
		$task = Task::findOne(1);
		$this->assertTrue($task->isAllowModify(Worker::findOne(1)));
		$this->assertFalse($task->isAllowModify(Worker::findOne(2)));
	}
	
	/**
	 * 测试设置分类
	 * @author KK
	 */
	public function testSetCategory() {
		$task = Task::findOne(1);
		$this->assertEquals(2, $task->{TaskCategory::tableName() . '_id'}); //如果migration mock的分类改了，则这里也要改一下
		try{
			$this->assertFalse($task->category = TaskCategory::findOne(11));
		}catch(InvalidParamException $e){
			$this->assertInstanceOf('yii\base\InvalidParamException', $e);
		}
		
		$newCategory = TaskCategory::findOne(3);
		$task->category = $newCategory;
		$this->assertEquals($newCategory->id, $task->category->id, '相同项目的分类，赋值成功');
	}
	
	/**
	 * 测试移动任务
	 * 由于testSetCategory测试已经将2号分类换成了11分类，所以这里将11号分类改回2号分类来测试
	 * @depends testIsAllowModify
	 * @depends testSetCategory
	 */
	public function testMove(){
		$form = new TaskForm(['scenario' => TaskForm::SCENE_MOVE]);
		$this->assertFalse($form->moveTask(), '不传任何参数会失败');
		$this->tester->assertHasKeys(['taskId', 'taskCategoryId'], $form->errors, '不传任何参数会失败');
		
		$form->load([
			'taskId' => 1,
			'taskCategoryId' => 2,
		], '');
		$form->worker = Worker::findOne(1);
		$this->assertInstanceOf(Task::className(), $form->moveTask());
	}
	
	/**
	 * 测试获取任务信息
	 * @author KK
	 */
	public function testInfo(){
		$form = new TaskForm(['scenario' => TaskForm::SCENE_INFO]);
		$this->assertFalse($form->getInfo(), '不传任何参数会失败');
		$this->tester->assertHasKeys(['taskId'], $form->errors, '缺少任务ID');
		
		$form->taskId = 9999;
		$this->assertFalse($form->getInfo(), '乱传ID也会失败');
		$this->tester->assertHasKeys(['taskId'], $form->errors, '这是个无效的任务ID');
		
		$form->taskId = 1;
		$taskInfo = $form->getInfo();
		$this->assertInternalType('array', $taskInfo);
		$this->tester->assertHasKeys(['id', 'title', 'detail', 'level', 'repeat', 'is_finish', 'limit_time', 'end_time', 'add_time', 'history'], $taskInfo, '不传任何参数会失败');
	}
}