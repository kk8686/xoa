<?php
namespace xoa_test\home\unit;

use xoa\common\models\{
	Project,
	Task
};
use xoa\home\forms\TaskForm;
use xoa\home\models\TaskCategory;

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
		$this->assertInstanceOf(Task::className(), $form->add(), '添加成功后会返回任务实例');
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
}