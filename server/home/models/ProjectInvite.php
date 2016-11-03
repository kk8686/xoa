<?php
namespace xoa\home\models;

/**
 * 项目邀请
 * @author KK
 */
class ProjectInvite extends \yii\db\ActiveRecord{
	/**
	 * 状态：待处理
	 */
	const STATUS_WAIT = 1;
	/**
	 * 状态：通过
	 */
	const STATUS_PASS = 2;
	/**
	 * 状态：拒绝
	 */
	const STATUS_REFUSED = 3;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName(){
		return 'project_invite';
	}
}