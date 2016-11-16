<?php
namespace xoa_test;

use xoa\common\models\Task;
use xoa\home\forms\TaskForm;
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

    public function testAdd(){
		$form = new TaskForm(['scenario' => TaskForm::SCENE_ADD]);
		$form->load([
			'title' => '',
			'detail' => '',
			'workerIds' => '',
			'relatedMemberIds' => '',
			'limit_time' => '',
		], '');
		$this->assertFalse($form->validate());
		$this->tester->assertHasKeys(['title', 'workerIds'], $form->errors);
		
		$form->detail = 'ab';
		$form->relatedMemberIds = 'ab';
		$this->assertFalse($form->validate());
		$this->tester->assertHasKeys(['title', 'detail', 'workerIds', 'relatedMemberIds'], $form->errors);
		
		$form->workerIds = [8888];
		$form->relatedMemberIds = [9999];
		$this->assertFalse($form->validate());
		$this->assertArrayHasKey('relatedMemberIds', $form->errors);
		
		$form->load([
			'title' => '测试任务',
			'detail' => '',
			'workerIds' => [2],
			'relatedMemberIds' => [3],
			'limit_time' => '',
		], '');
		$this->assertInstanceOf(Task::className(), $form->add());
    }
}