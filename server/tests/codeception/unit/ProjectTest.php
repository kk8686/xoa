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

    protected function _before()
    {
    }

    protected function _after()
    {
    }

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
}