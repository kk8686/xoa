<?php
namespace xoa\common\models;

use xoa\common\models\{
	Worker
};

/**
 * 通知
 * @author KK
 */
class Notice extends \yii\db\ActiveRecord{
	/**
	 * 类型：收到项目邀请
	 */
	const TYPE_NEW_INVITE = 1;
	
	/**
	 * 类型：有人接受了项目邀请
	 */
	const TYPE_INVITE_SUCCESS = 2;
	
	/**
	 * 类型：收到新任务
	 */
	const TYPE_NEW_TASK = 3;
	
	/**
	 * 类型：任务被移动分类了
	 */
	const TYPE_MOVE_TASK = 4;
	
	/**
	 * 类型：任务完成了
	 */
	const TYPE_FINISH_TASK = 5;
	
	/**
	 * 类型：有人反馈了
	 */
	const TYPE_NEW_FEEDBACK = 6;
	
	/**
	 * 类型：收到点评
	 */
	const TYPE_NEW_COMMENT = 7;
	
	/**
	 * 类型：任务被取消
	 */
	const TYPE_CANCEL_TASK = 8;
	
	/**
	 * 状态：未读
	 */
	const STATUS_UNREAD = 1;
	
	/**
	 * 状态：已读
	 */
	const STATUS_READ = 2;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName(){
		return '{{notice}}';
	}
	
	/**
	 * 阅读指定工作者的所有消息
	 * @param Worker $worker 工作者
	 */
	public static function readAll(Worker $worker){
		return static::updateAll(['status' => static::STATUS_READ], ['worker_id' => $worker->id]);
	}
}