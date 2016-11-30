<?php
namespace xoa_test\yii_ext;

use yii\db\Migration;
use xoa_test\_support\models\ArrayFieldModel as TestModel;

/**
 * 数组字段测试
 * @author KK
 */
class ArrayFieldTest extends \Codeception\TestCase\Test
{
    /**
     * @var \xoa_test\UnitTester
     */
    protected $tester;
	
	protected function _before(){
		if($this->getName() == 'testMain'){
			//临时建表
			$migrate = new Migration();
			$migrate->createTable(TestModel::tableName(), [
				'id' => $migrate->primaryKey(),
				'user_ids' => $migrate->string(),
				'history' => $migrate->text(),
			]);
		}
	}

    /**
	 * 主要测试
	 * @author KK
	 */
    public function testMain()
    {
		$testId = 1;
		$history = [
			[
				'type' => 2,
				'content' => 'xxyyzz'
			],
			[
				'type' => 3,
				'content' => 'aabbcc'
			],
		];
		(new TestModel([
			'user_ids' => [6, 33, 91],
			'history' => $history,
		]))->save();
		$this->tester->seeInDatabase(TestModel::tableName(), [
			'id' => $testId,
			'user_ids' => '6,33,91',
			'history' => json_encode($history),
		]);
		
		$model = TestModel::findOne($testId);
		$this->assertInternalType('array', $model->user_ids);
		$this->assertEquals(3, count($model->user_ids));
		$this->assertEquals(6, $model->user_ids[0]);
		$this->assertEquals(33, $model->user_ids[1]);
		$this->assertEquals(91, $model->user_ids[2]);
		
		$this->assertEquals(2, count($model->history));
		$this->assertEquals('aabbcc', $model->history[1]['content']);
		$history2 = $model->history;
		$history2[] = [
			'type' => 66,
			'content' => 'iijjkk'
		];
		$model->history = $history2;
		$this->assertTrue($model->save());
		$this->assertEquals(3, count(TestModel::findOne($testId)->history));
    }
}