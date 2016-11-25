<?php
namespace xoa\common\models;

use xoa\common\models\Worker;

/**
 * 任务
 * @author KK
 * @property-read array $workers 任务负责人集合
 */
class Task extends \yii\db\ActiveRecord{
	/**
	 * @inheritdoc
	 */
	public static function tableName(){
		return 'task';
	}
	
	/**
	 * 获取任务负责人集合
	 * @author KK
	 * @return array
	 */
	public function getWorkers(){
		return Worker::findAll(['id' => explode(',', $this->worker_ids)]);
	}
}
