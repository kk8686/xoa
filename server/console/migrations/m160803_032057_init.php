<?php
use xoa\common\models\{
	Project,
	ProjectInvite,
	Task,
	TaskCategory,
	Worker
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

	/**
	 * 回滚初始化迁移
	 * 之所以下面foreach去getTableSchema判断，是转为防止加了新表的create后，而又将drop都添加到safeDown中（大部分菜鸟会这样做），这样造成的后果就是他想重新构造新的数据库时，要先将以前的进行回滚，但回滚过程中会找不到他新添加的drop表于是变成了回滚失败，这里加个判断就方便一点了，维护也爽快点
	 * @author KK
	 */
    public function safeDown(){
		//要删除的表，新增了表的create后记得这里也加一个表
		$dropTables = [
			Project::tableName(),
			ProjectInvite::tableName(),
			Task::tableName(),
			TaskCategory::tableName(),
			Worker::tableName(),
		];
		
		//执行删除表
		foreach($dropTables as $dropTable){
			if(Yii::$app->db->schema->getTableSchema($dropTable)){
				$this->dropTable($dropTable);
			}
			
		}
    }
	
	/**
	 * 工作者表创建
	 * @author KK
	 */
	private function _worker_create(){
		$this->createTable(Worker::tableName(), [
			'id' => $this->primaryKey(),
			//账号安全类
			'email' => $this->string(255)->notNull()->defaultValue('')->comment('邮箱，可以用于登陆'),
			'mobile' => $this->string(11)->notNull()->defaultValue('')->comment('手机号，可以用于登陆，未实现'),
			'password_hash' => $this->string(64)->notNull()->defaultValue('')->comment('密码hash'),
			'hash_key' => $this->string(64)->notNull()->defaultValue('')->comment('员工专属的hash密钥，可以用来hash其它东西'),
			
			//个人属性类
			'name' => $this->string(255)->notNull()->defaultValue('')->comment('姓名'),
			'avatar' => $this->string(255)->notNull()->defaultValue('')->comment('头像'),
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
		$fields = ['id', 'email', 'mobile', 'password_hash', 'hash_key', 'name', 'avatar', 'gender', 'birthday', 'add_time'];
		Yii::$app->db->createCommand()->batchInsert(Worker::tableName(), $fields, [
			//手动调试测试专用，项目管理员
			[1, '12@12.com', '13800138000', $passwordHash, $hashKey, '陈莹莹', '/data/worker/avatar/1.jpg', Worker::GENDER_FEMALE, '1995-05-05', $today],
			
			//自动化测试专用
			[2, '99@99.com', '13800138099', $passwordHash, $hashKey, '王自动', '/data/worker/avatar/2.jpg', Worker::GENDER_MALE, $today, $today],
			
			//手动调试测试专用，普通员工
			[3, '13@12.com', '13800138001', $passwordHash, $hashKey, '叶聪', '/data/worker/avatar/3.jpg', Worker::GENDER_MALE, '1997-12-23', $today],
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
		Yii::$app->db->createCommand()->batchInsert(Project::tableName(), ['id', 'name', Worker::tableName() . '_id', 'member_ids', 'add_time'], [
			[1, '兔子外卖', 1, '2', date('Y-m-d')],
			[2, '嘟嘟打车', 1, '', date('Y-m-d')],
			[3, '去那儿', 1, '', date('Y-m-d')],
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
			
			[11, '2号项目的测试分类1', 2, 1],
		])->execute();
	}
	
	/**
	 * 任务表创建
	 * @author KK
	 */
	private function _task_create() {
		$this->createTable(Task::tableName(), [
			'id' => $this->primaryKey(),
			Project::tableName() . '_id' => $this->integer()->notNull()->defaultValue(0)->comment('所属项目的ID'),
			TaskCategory::tableName() . '_id' => $this->integer()->notNull()->defaultValue(0)->comment('所属的分类ID'),
			'creater_id' => $this->integer()->notNull()->defaultValue(0)->comment('创建者ID'),
			Worker::tableName() . '_ids' => $this->string()->notNull()->defaultValue('')->comment('负责人ID集，逗号隔开'),
			'related_member_ids' => $this->string()->notNull()->defaultValue('')->comment('相关人员ID集，逗号隔开'),
			'title' => $this->string(30)->notNull()->defaultValue('')->comment('标题'),
			'detail' => $this->text()->notNull()->defaultValue('')->comment('详情'),
			'ok' => $this->boolean()->notNull()->defaultValue(false)->comment('是否搞定了'),
			'order' => $this->smallInteger()->notNull()->defaultValue(0)->comment('排序'),
			'limit_time' => $this->dateTime()->notNull()->defaultValue(0)->comment('要求完成时间'),
			'end_time' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00')->comment('实际完成时间'),
			'add_time' => $this->dateTime()->notNull()->defaultValue('')->comment('发布时间'),
		]);
	}
	
	/**
	 * 任务表数据模拟
	 * @author KK
	 */
	private function _task_mock() {
		$fields = [
			'id',
			Project::tableName() . '_id',
			TaskCategory::tableName() . '_id',
			'creater_id',
			Worker::tableName() . '_ids',
			'related_member_ids',
			'title',
			'detail',
			'ok',
			'order',
			'limit_time',
			'end_time',
			'add_time'
		];
		Yii::$app->db->createCommand()->batchInsert(Task::tableName(), $fields, [
			[1, 1, 2, 1, '1', '', '修复登陆验证码错误3次后没有冻结账户的问题', '', false, 1, date('Y-m-d H:i:s', strtotime('+1day')), '0000-00-00 00:00:00', date('Y-m-d H:i:s')],
			[2, 1, 2, 1, '1,2', '', '新增团购功能，仿XX网站', '', false, 1, date('Y-m-d H:i:s', strtotime('+7day')), '0000-00-00 00:00:00', date('Y-m-d H:i:s')],
			[3, 1, 1, 1, '3', '', '准备一套mock数据，周六路演要用', '', false, 1, date('Y-m-d H:i:s', strtotime('+7day')), '0000-00-00 00:00:00', date('Y-m-d H:i:s')],
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
			Worker::tableName() . '_id' => $this->integer()->notNull()->defaultValue(0)->comment('分类ID'),
			'status' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('添加时间'),
			'add_time' => $this->date()->notNull()->comment('添加时间'),
		]);
	}
}