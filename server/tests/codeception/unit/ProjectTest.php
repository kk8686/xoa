<?php
namespace xoa_test\home\unit;

use xoa\common\models\{
	Project,
	Worker
};
use xoa\home\forms\ProjectForm;

class ProjectTest extends \Codeception\TestCase\Test
{
    /**
     * @var \xoa_test\UnitTester
     */
    protected $tester;

    /**
	 * 测试添加项目
	 * @author KK
	 */
    public function testAdd()
    {
		$form = new ProjectForm([
			'scenario' => ProjectForm::SCENE_ADD,
			'name' => '',
			'creater' => Worker::findOne(1),
		]);
		$this->assertFalse($form->validate());
		$this->assertArrayHasKey('name', $form->errors, '包含项目名称的错误');
		
		$form->name = [1,2,3];
		$this->assertFalse($form->validate());
		$this->assertArrayHasKey('name', $form->errors, '项目名称应该是一个字符串');
		
		$form->name = 'test';
		$project = $form->add();
		$this->assertInstanceOf(Project::className(), $project, '注册后应返回Project实例');
		
		$this->tester->seeInDatabase(Project::tableName(), ['id' => $project->id], '注册后数据库应该有这个ID的记录');
    }
	
	/**
	 * 测试邀请项目成员
	 * @author KK
	 */
	public function testInviteMember() {
		$form = new ProjectForm([
			'scenario' => ProjectForm::SCENE_INVITE_MEMBER,
		]);
		$this->assertFalse($form->validate(), '什么属性都不填肯定是不行的');
		$this->assertArrayHasKey('projectId', $form->errors, '包含项目ID的错误');
		$this->assertArrayHasKey('inviteWorkerId', $form->errors, '包含被邀请工作者ID的错误');
		
		$form->load([
			'projectId' => 'aa',
			'inviteWorkerId' => 'bb',
		], '');
		$this->assertFalse($form->validate());
		$this->assertArrayHasKey('projectId', $form->errors, '包含项目ID的错误');
		$this->assertArrayHasKey('inviteWorkerId', $form->errors, '包含被邀请工作者ID的错误');
		
		$form->load([
			'projectId' => 1,
			'inviteWorkerId' => 2,
		], '');
		$this->assertTrue($form->inviteMember(), '第一次邀请会成功');
		
		$this->assertFalse($form->inviteMember(), '已经邀请过');
	}
}