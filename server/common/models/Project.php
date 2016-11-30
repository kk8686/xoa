<?php
namespace xoa\common\models;

use xoa\common\models\{
	Worker,
	TaskCategory
};
use xoa\common\ext\behaviors\ArrayField;

/**
 * 项目
 * @author KK
 * @property-read array $taskCategories 项目的所有分类
 * @property array $member_ids 项目成员的ID集
 * @property-read array $workers 项目成员集合
 */
class Project extends \yii\db\ActiveRecord{
	/**
	 * @inheritdoc
	 */
	public static function tableName(){
		return 'project';
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors(){
		return [
			[
				'class' => ArrayField::className(),
				'fields' => [
					'member_ids' => ArrayField::TYPE_COMMA,
				],
			],
		];
	}
	
	/**
	 * 获取项目的所有任务分类
	 * @author KK
	 * @return array
	 */
	public function getTaskCategories(){
		return $this->hasMany(TaskCategory::className(), [static::tableName() . '_id' => 'id']);
	}
	
	/**
	 * 获取项目成员集合
	 * @author KK
	 * @return array
	 */
	public function getWorkers(){
		$workerIds = array_merge([$this->worker_id], $this->member_ids);
		return Worker::findAll(['id' => $workerIds]);
	}
}
