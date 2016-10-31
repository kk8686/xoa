<?php
use xoa\common\models\{
	Project,
	Worker
};

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
	
	public function mockData() {
		$migrateReflection = new \ReflectionClass(self::className());
		foreach($migrateReflection->getMethods(\ReflectionMethod::IS_PRIVATE) as $method){
			if(substr($method->getName(), -5) == '_mock'){
				$method->setAccessible(true);
				$method->invoke($migrateReflection->newInstanceArgs());
			}
		}
	}

    public function safeDown(){
		$this->dropTable(Worker::tableName());
		$this->dropTable(Project::tableName());
    }
	
	private function _worker_create(){
		$this->createTable(Worker::tableName(), [
			'id' => $this->primaryKey(),
			//账号安全类
			'email' => $this->string(255)->notNull()->comment('邮箱，可以用于登陆'),
			'mobile' => $this->string(11)->notNull()->comment('手机号，可以用于登陆，未实现'),
			'password_hash' => $this->string(64)->notNull()->comment('密码hash'),
			'hash_key' => $this->string(64)->notNull()->comment('员工专属的hash密钥，可以用来hash其它东西'),
			
			//个人属性类
			'name' => $this->string(255)->notNull()->comment('姓名'),
			'gender' => $this->boolean()->notNull()->defaultValue(0)->comment('姓名'),
			'birthday' => $this->date()->notNull()->comment('出生日期'),
			'add_time' => $this->date()->notNull()->comment('添加日期'),
		]);
	}
	
	private function _worker_mock(){
		$hashKey = Yii::$app->security->generateRandomString();
		$passwordHash = Yii::$app->security->generatePasswordHash('121212' . $hashKey);
		
		$today = date('Y-m-d');
		$fields = ['id', 'email', 'mobile', 'password_hash', 'hash_key', 'name', 'gender', 'birthday', 'add_time'];
		Yii::$app->db->createCommand()->batchInsert(Worker::tableName(), $fields, [
			[1, '12@12.com', '13800138000', $passwordHash, $hashKey, '陈莹莹', Worker::GENDER_FEMALE, '1995-05-05', $today], //手动调试测试专用
			[2, '99@99.com', '13800138099', $passwordHash, $hashKey, '王自动', Worker::GENDER_MALE, $today, $today], //自动化测试专用
		])->execute();
	}
	
	private function _project_create(){
		$this->createTable(Project::tableName(), [
			'id' => $this->primaryKey(),
			'name' => $this->string(30)->notNull()->comment('名称'),
			Worker::tableName() . '_id' => $this->integer()->notNull()->comment('创建者的ID'),
			'add_time' => $this->date()->notNull()->comment('添加日期'),
		]);
	}
	
	private function _project_mock(){
		Yii::$app->db->createCommand()->batchInsert(Project::tableName(), ['id', 'name', Worker::tableName() . '_id', 'add_time'], [
			[1, '兔子外卖', 1, date('Y-m-d')],
			[2, '嘟嘟打车', 1, date('Y-m-d')],
			[3, '去那儿', 1, date('Y-m-d')],
		])->execute();
	}
}