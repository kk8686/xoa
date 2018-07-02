<?php
namespace xoa\home\forms;

use yii\db\Query;
use xoa\common\models\{
	Project,
	ProjectInvite,
	Worker
};

/**
 * 项目表单
 * @author KK
 */
class ProjectForm extends \yii\base\Model{
	/**
	 * 场景：添加项目
	 */
	const SCENE_ADD = 'add';
	
	/**
	 * 场景：邀请新的项目成员加入
	 */
	const SCENE_INVITE_MEMBER = 'invite_member';
	/**
	 * @var string 项目名称
	 */
	public $name = '';

	/**
	 * @var Worker 创建者
	 */
	public $creater = null;
	
	/**
	 * @var int 项目ID
	 */
	public $projectId = 0;

	/**
	 * @var Project 项目
	 */
	public $project = null;
	
	/**
	 * @var int 被邀请加入项目的工作者ID
	 */
	public $inviteWorkerId = 0;
	
	/**
	 * @var Worker 工作者
	 */
	public $worker = null;
	
	/**
	 * @inheritdoc
	 */
	public function rules(){
		return [
			[['name', 'creater', 'projectId', 'inviteWorkerId'], 'required'],
			['name', 'string', 'length' => [1, 30]],
			['name', 'validateSameName'],
			[['projectId', 'inviteWorkerId'], 'integer', 'message' => '无效的项目ID'],
			['projectId', 'exist', 'targetClass' => Project::className(), 'targetAttribute' => 'id', 'message' => '无效的项目ID'],
			['inviteWorkerId', 'validateWorkerId'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		return [
			static::SCENE_ADD => ['name', 'creater'],
			static::SCENE_INVITE_MEMBER => ['projectId', 'inviteWorkerId'],
		];
	}
	
	/**
	 * 检查当前用户是否创建过相同名字的项目名称
	 * @author KK
	 */
	public function validateSameName() {
		$exists = (new Query())->from(\xoa\common\models\Project::tableName())->where([
			'name' => $this->name,
			'worker_id' => $this->creater->id,
		])->exists();
		if($exists){
			$this->addError('name', '您已经创建过一个相同名称的项目了');
		}
	}
	
	
	/**
	 * 检查要邀请的工作者ID是否有效
	 * @author KK
	 */
	public function validateWorkerId() {
		if(!$worker = Worker::findOne($this->inviteWorkerId)){
			$this->addError('inviteWorkerId', '(⊙_⊙)? 我怎么找不到这个人呢，麻烦再确认一下ID对不对吧');
			return;
		}
		$this->worker = $worker;
	}
	
	/**
	 * 添加项目
	 * @author KK
	 * @return Project
	 * @throws \yii\base\ErrorException
	 * @test \xoa_test\home\unit\ProjectTest::testAdd
	 */
	public function add() : bool{
		if(!$this->validate()){
			return false;
		}
		
		$project = new Project([
			'name' => $this->name,
			'worker_id' => $this->creater->id,
			'member_ids' => [],
			'add_time' => date('Y-m-d'),
		]);
		
		if(!$project->save()){
			throw new \yii\base\ErrorException('添加项目失败');
		}
		$this->project = $project;
		return true;
	}
	
	/**
	 * 邀请新的项目成员
	 * @author KK
	 * @return boolean 是否邀请成功
	 * @throws \yii\base\ErrorException
	 */
	public function inviteMember() : bool{
		if(!$this->validate()){
			return false;
		}
		
		//检查是否邀请过
		if(ProjectInvite::find()->where([
			'project_id' => $this->projectId,
			'worker_id' => $this->worker->id,
		])->exists()){
			$this->addError('invited', '喔喔喔已经邀请过这个人了 →_→ 你肿么老是忘记呀？' . PHP_EOL . '最近有按时睡觉咩');
			return false;
		}
		
		$projectInvite = new ProjectInvite([
			'project_id' => $this->projectId,
			'worker_id' => $this->worker->id,
			'status' => ProjectInvite::STATUS_WAIT,
			'add_time' => date('Y-m-d'),
		]);
		if(!$projectInvite->save()){
			throw new \yii\base\ErrorException('邀请项目成员失败');
		}
		return true;
	}
}