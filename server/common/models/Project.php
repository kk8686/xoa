<?php
namespace xoa\common\models;

use xoa\home\models\TaskCategory;

/**
 * 项目
 * @author KK
 * @property-read array $taskCategories 项目的所有分类
 * @property array $member_ids 项目成员的ID集
 */
class Project extends \yii\db\ActiveRecord{
	/**
	 * @inheritdoc
	 */
	public static function tableName(){
		return 'project';
	}
	
	/**
	 * 获取项目的所有任务分类
	 * @author KK
	 * @return array
	 */
	public function getTaskCategories(){
		return $this->hasMany(TaskCategory::className(), [static::tableName() . '_id' => 'id']);
	}
}
