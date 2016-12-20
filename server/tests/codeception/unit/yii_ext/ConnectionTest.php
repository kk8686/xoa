<?php
namespace xoa_test\home\unit\yii_ext;

use Yii;
use PDO;
use xoa\common\ext\db\Connection;
use yii\db\{
	Expression,
	Query
};
use xoa\common\models\{
	ProjectInvite,
	Worker
};

/**
 * Db组件测试
 * @author KK
 */
class ConnectionTest extends \Codeception\TestCase\Test
{
    /**
     * @var \xoa_test\UnitTester
     */
    protected $tester;

    protected function _before(){
		Yii::$app->set('db', Yii::createObject(Yii::$app->components['db'])); //不知为什么运行过ArrayFieldTest就会导致下面无法执行SQL，重新初始化db组件就行了，是不是ArrayFieldTest哪里影响了db组件呢？暂时查不出，把这里的SQL放在ArrayFieldTest的底部执行是没问题的，可是放在这个测试用例里就有问题了
		
		$this->assertInstanceOf(Connection::className(), Yii::$app->db);
    }

	/**
	 * 测试获取上一条SQL语句
	 * @author KK
	 */
	public function testGetLastSqls() {
		//表达式查询
		$num1 = 1;
		$num2 = 2;
		$query1 = (new Query())->select(['result' => new Expression($num1 . '+' . $num2)]);
		$command1 = $query1->createCommand();
		$command1->execute();
		$this->assertEquals($num1 + $num2, $command1->pdoStatement->fetch(PDO::FETCH_ASSOC)['result'], 'SQL执行失败，MySql的话可能因为服务器繁忙');
		$lastSqls = Yii::$app->db->getLastSqls();
		$this->assertInternalType('array', $lastSqls, '获取上一条的时候返回了非数组');
		$this->assertEquals(1, count($lastSqls), '获取上一条失败');
		$this->assertEquals($command1->rawSql, $lastSqls[0], '上一条SQL语句不相同');

		
		//从表里查询
		$command2 =	(new Query())
						->select(['id'])
						->from(Worker::tableName())
						->where(['id' => 1])->createCommand();
		$command2->execute();
		$lastSqls2 = Yii::$app->db->getLastSqls();
		$this->assertInternalType('array', $lastSqls2, '第2次获取上一条的时候返回了非数组');
		$this->assertEquals(1, count($lastSqls2), '第2次获取上一条失败');
		$this->assertEquals($command2->rawSql, $lastSqls2[0], '上一条SQL语句不相同');


		$lastSqls3 = Yii::$app->db->getLastSqls(2);
		$this->assertInternalType('array', $lastSqls3, '获取上两条失败');
		//获取多条
		$this->assertEquals([
			$command2->rawSql,
			$command1->rawSql,
		], $lastSqls3, '上两条SQL不匹配');


		//在DML语句执行下的多条获取
		$command3 =	(new Query())
						->createCommand()
						->insert(ProjectInvite::tableName(), [
							'project_id' => 99,
							'worker_id' => 99,
							'add_time' => '0000-00-00 00:00:00',
						]);
		$command3->execute();
		
		if(Yii::$app->db->driverName == 'sqlite'){
			//sqlite会多出2条外键查询语句
			$lastSqls4 = Yii::$app->db->getLastSqls(5);
			$this->assertEquals(5, count($lastSqls4));
			$this->assertEquals($command3->rawSql, $lastSqls4[0]);
			$this->assertEquals($command2->rawSql, $lastSqls4[3]);
			$this->assertEquals($command1->rawSql, $lastSqls4[4]);
		}else{
			
			$lastSqls5 = Yii::$app->db->getLastSqls(3);
			$this->assertInternalType('array', $lastSqls5, '在有DML语句的情况下获取上三条失败');
			$this->assertEquals([
				$command3->rawSql,
				$command2->rawSql,
				$command1->rawSql,
			], $lastSqls5, '上3条SQL语句不匹配,实际数据是' . PHP_EOL . var_export($lastSqls5, true));
		}

		//关键字获取
		$lastSqls6 = Yii::$app->db->getLastSqls(1, 'worker');
		$this->assertInternalType('array', $lastSqls6, '在有DML语句的情况下获取上三条失败');
		$this->assertEquals($command3->rawSql, $lastSqls6[0], '按关键字获取失败');
	}
}