<?php
namespace xoa_test;

use xoa\common\models\Worker;
use xoa\common\forms\RegisterForm;

class WorkerTest extends \Codeception\TestCase\Test
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
	 * 测试注册
	 * @author KK
	 */
    public function testRegister()
    {
		$form = new RegisterForm([
			'email' => '',
			'password' => '',
		]);
		$this->assertFalse($form->validate());
		$this->assertArrayHasKey('email', $form->errors, '包含邮箱的错误');
		$this->assertArrayHasKey('password', $form->errors, '包含密码的错误');
		
		$form->email = 'xx@yy.com';
		$form->password = 'abc';
		$this->assertFalse($form->validate());
		$this->assertArrayHasKey('password', $form->errors, '包含密码的错误');
		$this->assertArrayNotHasKey('email', $form->errors, '不会包含邮箱的错误');
		
		$form->password = '121212';
		$worker = $form->register();
		$this->assertInstanceOf(Worker::className(), $worker, '注册后应返回worker实例');
		
		$this->tester->seeInDatabase(Worker::tableName(), ['id' => $worker->id], '注册后数据库应该有这个ID的记录');
    }
}