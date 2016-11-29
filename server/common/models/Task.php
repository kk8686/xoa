<?php
namespace xoa\common\models;

use yii\db\Expression;
use xoa\common\models\{
	TaskCategory,
	Worker
};

/**
 * 任务
 * @author KK
 * @property-read array $workers 任务负责人集合
 * @property-read TaskCategory $category 任务负责人集合
 * @property-read Worker $creater 任务负责人集合
 */
class Task extends \yii\db\ActiveRecord{
	/**
	 * 级别：有空处理
	 */
	const LEVEL_WAIT = 1;
	
	/**
	 * 级别：不急
	 */
	const LEVEL_NOT_URGENT = 2;
	
	/**
	 * 级别：普通
	 */
	const LEVEL_NORMAL = 3;
	
	/**
	 * 级别：紧急urgent 
	 */
	const LEVEL_URGENT = 4;
	
	/**
	 * 级别：非常紧急
	 */
	const LEVEL_SO_URGENT = 5;
	
	/**
	 * 周期：不重复
	 */
	const REPEAT_NO = 1;
	
	/**
	 * 周期：工作日
	 */
	const REPEAT_WORK_DAY = 2;
	
	/**
	 * 周期：每天
	 */
	const REPEAT_EVERY_DAY = 3;
	
	/**
	 * 周期：每周
	 */
	const REPEAT_EVERY_WEEK = 4;
	
	/**
	 * 周期：每月
	 */
	const REPEAT_EVERY_MONTH = 5;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName(){
		return 'task';
	}
	
	/**
	 * 获取取得周期集合
	 * @labels
	 * @author KK
	 * @return array
	 */
	public static function repeats(){
		return [
			static::REPEAT_NO => '不重复',
			static::REPEAT_WORK_DAY => '工作日',
			static::REPEAT_EVERY_DAY => '每天',
			static::REPEAT_EVERY_WEEK => '每周',
			static::REPEAT_EVERY_MONTH => '每月',
		];
	}
	
	/**
	 * 获取级别集合
	 * @labels
	 * @author KK
	 * @return array
	 */
	public static function levels(){
		return [
			static::LEVEL_WAIT => '有空处理',
			static::LEVEL_NOT_URGENT => '不急',
			static::LEVEL_NORMAL => '普通',
			static::LEVEL_URGENT => '紧急',
			static::LEVEL_SO_URGENT => '非常紧急',
		];
	}
	
	/**
	 * 获取负责人集合
	 * @author KK
	 * @return array
	 */
	public function getWorkers(){
		return Worker::findAll(['id' => explode(',', $this->worker_ids)]);
	}
	
	/**
	 * 获取创建者
	 * @author KK
	 * @return Worker
	 */
	public function getCreater(){
		return $this->hasOne(Worker::className(), ['id' => 'creater_id']);
	}
	
	/**
	 * 获取相关的成员集
	 * @author KK
	 * @return array
	 */
	public function getRelatedMembers(){
		return $this->related_member_ids ? Worker::findAll(['id' => explode(',', $this->related_member_ids)]) : [];
	}
	
	/**
	 * 获取任务的分类
	 * @author KK
	 * @return TaskCategory
	 */
	public function getCategory(){
		return $this->hasOne(TaskCategory::className(), ['id' => TaskCategory::tableName() . '_id']);
	}
	
	/**
	 * 设置任务的新分类
	 * 必须是同一个项目下的分类，不支持跨项目设置
	 * @author KK
	 * @param TaskCategory $category
	 * @return boolean
	 * @throws \yii\base\InvalidParamException
	 * @test \xoa_test\home\unit\TaskTest::testSetCategory
	 */
	public function setCategory(TaskCategory $category){
		if($this->category->project->id != $category->project->id){
			throw new \yii\base\InvalidParamException('新分类与旧分类不是同一个项目，无法跨项目移动分类');
		}
		$this->task_category_id = $category->id;
		$result = (bool)$this->save();
		$this->refresh();
		return $result;
	}
	
	/**
	 * 判断一个工作者是否允许修改任务
	 * @author KK
	 * @param Worker 要被判断的工作者
	 * @test \xoa_test\home\unit\TaskTest::testIsAllowModify
	 */
	public function isAllowModify(Worker $worker){
		$isCreater = $worker->id == $this->creater_id;
		$isWorker = in_array($worker->id, explode(',', $this->worker_ids));
		return $isCreater || $isWorker;
	}
	
	/**
	 * 排序到指定位置
	 * @author KK
	 * @param int $order
	 * @return boolean
	 * @test \xoa_test\home\unit\TaskTest::testOrderTo
	 */
	public function orderTo(int $order){
		$query = static::find()->select(['id'])->where([
			'and',
			['<>', 'id', $this->id],
			['>=', 'order', $order],
			['=', 'task_category_id', $this->task_category_id],
		]);
		static::updateAll(['order' => new Expression('`order` + 1')], ['in', 'id', $query]);
		
		$this->order = $order;
		return (bool)$this->save();
	}
}
