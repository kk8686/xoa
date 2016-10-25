<?php
use xoa\common\models\Worker;

class m160803_032057_init extends yii\db\Migration{
    public function safeUp(){
		$migrateReflection = new ReflectionClass(self::className());
		foreach($migrateReflection->getMethods(ReflectionMethod::IS_PRIVATE) as $method){
			if(substr($method->getName(), -7) === '_create'){
				$method->setAccessible(true);
				$method->invoke($migrateReflection->newInstanceArgs());
			}
		}
    }

    public function safeDown(){
		$this->dropTable(Worker::tableName());
    }
	
	private function _worker_create(){
		$this->createTable(Worker::tableName(), [
			'id' => $this->primaryKey(),
			'mobile' => $this->string(11)->notNull()->comment('手机号'),
			'email' => $this->string(255)->notNull()->comment('邮箱'),
			'password_hash' => $this->string(64)->notNull()->comment('密码hash'),
			'hash_key' => $this->string(64)->notNull()->comment('员工专属的hash密钥，可以用来hash其它东西'),
			'name' => $this->string(255)->notNull()->comment('姓名'),
			'add_time' => $this->date()->notNull()->comment('添加日期'),
		]);
		
		/*
		$passwordHashKey = Yii::$app->security->generateRandomString();
		$passwordHash = Yii::$app->security->generatePasswordHash('121212' . $passwordHashKey);
		
		(new Worker([
			'email' => 'root@xoa.com',
			'email' => 'root@xoa.com',
			'password_hash' => $passwordHash,
			'password_hash_key' => $passwordHashKey,
			'add_time' => date('Y-m-d'),
		]))->save();*/
	}
}
