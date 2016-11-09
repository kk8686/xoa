<?php
namespace xoa\common\forms;

use xoa\common\models\Worker;

/**
 * 注册表单
 * @author KK
 */
class RegisterForm extends \yii\base\Model{
	/**
	 * @var string 邮箱
	 */
	public $email = '';
	/**
	 * @var string 密码
	 */
	public $password = '';
	
	public function rules(){
		return [
			[['email', 'password'], 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => 'xoa\common\models\Worker'],
			['password', 'string', 'length' => [6, 16]],
		];
	}
	
	
	/**
	 * 注册新的工作者
	 * @author KK
	 * @return Worker
	 * @throws \yii\base\ErrorException
	 * @test \xoa_test\home\unit\WorkerTest
	 */
	public function register() : Worker{
		$passwordInfo = Worker::generatePassword($this->password);
		$worker = new Worker([
			'email' => $this->email,
			'password_hash' => $passwordInfo['password_hash'],
			'hash_key' => $passwordInfo['hash_key'],
			'birthday' => '0000-00-00',
			'add_time' => date('Y-m-d'),
			'mobile' => '',
			'name' => '',
		]);
		if($worker->save()){
			return $worker;
		}else{
			throw new \yii\base\ErrorException('注册失败');
		}
	}
}