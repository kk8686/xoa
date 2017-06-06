<?php
namespace xoa\home\forms;

use xoa\common\models\{
	Notice
};

/**
 * 项目表单
 * @author KK
 */
class SystemForm extends \yii\base\Model{
	/**
	 * 场景：检查通知
	 */
	const SCENE_CHECK_NOTICE = 'check_notice';
	
	/**
	 * @var \xoa\common\models\Worker 工作者
	 */
	public $worker;
	
	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		return [
			static::SCENE_CHECK_NOTICE => [],
		];
	}
	
	/**
	 * 检查当前用户是否创建过相同名字的项目名称
	 * @author KK
	 * @test xoa_test\unit\SystemTest::testCheckNotice
	 */
	public function checkNotice() {
		return Notice::find()->where([
			'worker_id' => $this->worker->id,
			'status' => Notice::STATUS_UNREAD,
		])->exists();
	}
}