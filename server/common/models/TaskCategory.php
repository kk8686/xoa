<?php
namespace xoa\common\models;

use xoa\common\models\Project;

/**
 * 任务分类
 * @author KK
 * @property-read Project $project 所属的项目
 */
class TaskCategory extends \yii\db\ActiveRecord{
	public static function tableName(){
		return 'task_category';
	}
	
	/**
	 * 获取所属的项目
	 * @author KK
	 * @return Project
	 * @test \xoa_test\home\unit\TaskTest::testTaskCategory
	 */
	public function getProject(){
		return $this->hasOne(Project::className(), [ 'id' => Project::tableName() . '_id']);
	}
}