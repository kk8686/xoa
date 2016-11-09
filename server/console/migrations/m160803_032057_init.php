<?php
use xoa\common\models\{
	Project,
	Worker
};

use xoa\home\models\{
	ProjectInvite,
	TaskCategory
};

/**
 * 初始化迁移
 * @author KK
 */
class m160803_032057_init extends yii\db\Migration{
	/**
	 * 执行建表
	 * @author KK
	 */
    public function safeUp(){
		$migrateReflection = new ReflectionClass(self::className());
		foreach($migrateReflection->getMethods(ReflectionMethod::IS_PRIVATE) as $method){
			if(substr($method->getName(), -7) === '_create'){
				$method->setAccessible(true);
				$method->invoke($migrateReflection->newInstanceArgs());
			}
		}
    }
	
	/**
	 * 执行模拟数据
	 * @author KK
	 */
	public function mockData() {
		$controller = Yii::$app->controller;
		$controller->stdout(PHP_EOL . PHP_EOL . 'Start to mock data' . PHP_EOL);
		$migrateReflection = new \ReflectionClass(self::className());
		foreach($migrateReflection->getMethods(ReflectionMethod::IS_PRIVATE) as $method){
			if(
				preg_match('#^_(.+)_mock$#', $method->getName(), $matchResult)){
				$controller->stdout('    > mock ' . $matchResult[1] . ' ...');
				$method->setAccessible(true);
				$method->invoke($migrateReflection->newInstanceArgs());
				$controller->stdout(' done' . PHP_EOL);
			}
		}
	}

    public function safeDown(){
		$this->dropTable(Worker::tableName());
		$this->dropTable(Project::tableName());
		$this->dropTable(TaskCategory::tableName());
		$this->dropTable(ProjectInvite::tableName());
    }
	
	/**
	 * 工作者表创建
	 * @author KK
	 */
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
			'birthday' => $this->date()->notNull()->defaultValue('0000-00-00')->comment('出生日期'),
			'add_time' => $this->date()->notNull()->comment('添加日期'),
		]);
	}
	
	
	/**
	 * 工作者表模拟数据
	 * @author KK
	 */
	private function _worker_mock(){
		$hashKey = Yii::$app->security->generateRandomString();
		$passwordHash = Yii::$app->security->generatePasswordHash('121212' . $hashKey);
		
		$today = date('Y-m-d');
		$fields = ['id', 'email', 'mobile', 'password_hash', 'hash_key', 'name', 'gender', 'birthday', 'add_time'];
		Yii::$app->db->createCommand()->batchInsert(Worker::tableName(), $fields, [
			//手动调试测试专用，项目管理员
			[1, '12@12.com', '13800138000', $passwordHash, $hashKey, '陈莹莹', Worker::GENDER_FEMALE, '1995-05-05', $today],
			
			//自动化测试专用
			[2, '99@99.com', '13800138099', $passwordHash, $hashKey, '王自动', Worker::GENDER_MALE, $today, $today],
			
			//手动调试测试专用，普通员工
			[3, '13@12.com', '13800138001', $passwordHash, $hashKey, '叶聪', Worker::GENDER_MALE, '1997-12-23', $today],
		])->execute();
	}
	
	
	/**
	 * 项目表创建
	 * @author KK
	 */
	private function _project_create(){
		$this->createTable(Project::tableName(), [
			'id' => $this->primaryKey(),
			'name' => $this->string(30)->notNull()->comment('名称'),
			Worker::tableName() . '_id' => $this->integer()->notNull()->defaultValue(0)->comment('创建者的ID'),
			'member_ids' => $this->string(255)->notNull()->defaultValue('')->comment('成员的ID集合'),
			'add_time' => $this->date()->notNull()->comment('添加日期'),
		]);
	}
	
	
	/**
	 * 项目表模拟数据
	 * @author KK
	 */
	private function _project_mock(){
		Yii::$app->db->createCommand()->batchInsert(Project::tableName(), ['id', 'name', Worker::tableName() . '_id', 'add_time'], [
			[1, '兔子外卖', 1, date('Y-m-d')],
			[2, '嘟嘟打车', 1, date('Y-m-d')],
			[3, '去那儿', 1, date('Y-m-d')],
		])->execute();
	}
	
	
	/**
	 * 任务分类表创建
	 * @author KK
	 */
	private function _task_category_create(){
		$this->createTable(TaskCategory::tableName(), [
			'id' => $this->primaryKey(),
			'name' => $this->string(30)->notNull()->comment('名称'),
			Project::tableName() . '_id' => $this->integer()->notNull()->defaultValue(0)->comment('所属项目的ID'),
			'order' => $this->smallInteger()->notNull()->defaultValue(0)->comment('排序'),
		]);
	}
	
	
	/**
	 * 任务分类表模拟数据
	 * @author KK
	 */
	private function _task_category_mock(){
		Yii::$app->db->createCommand()->batchInsert(TaskCategory::tableName(), ['id', 'name', Project::tableName() . '_id', 'order'], [
			[1, '待处理', 1, 1],
			[2, '进行中', 1, 2],
			[3, '返修', 1, 3],
			[4, '已验收', 1, 4],
			[5, '已验收（返修）', 1, 5],
		])->execute();
	}
	
	
	/**
	 * 项目加入邀请表创建
	 * @author KK
	 */
	private function _project_invite_create(){
		$this->createTable(ProjectInvite::tableName(), [
			'id' => $this->primaryKey(),
			Project::tableName() . '_id' => $this->integer()->notNull()->defaultValue(0)->comment('项目ID'),
			Worker::tableName() . '_id' => $this->integer()->notNull()->defaultValue(0)->comment('工作者ID'),
			'status' => $this->boolean()->notNull()->defaultValue(0)->comment('状态 1=待处理，2=通过，3=拒绝'),
			'add_time' => $this->date()->notNull()->comment('邀请时间'),
		]);
	}
}