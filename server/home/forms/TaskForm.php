<?php
namespace xoa\home\forms;

use xoa\common\models\{
	Project,
	Task,
	TaskCategory,
	Worker
};
use yii\helpers\ArrayHelper;

/**
 * 任务表单
 * @author KK
 */
class TaskForm extends \yii\base\Model{
	/**
	 * 场景：添加任务
	 */
	const SCENE_ADD = 'add';
	
	/**
	 * 场景：任务列表
	 */
	const SCENE_LIST = 'list';
	
	/**
	 * 场景：任务信息
	 */
	const SCENE_INFO = 'info';
	
	/**
	 * 场景：移动任务
	 */
	const SCENE_MOVE = 'move';
	
	/**
	 * @var int 任务ID
	 */
	public $taskId = 0;
	
	/**
	 * @var string 任务标题
	 */
	public $title = '';
	
	/**
	 * @var string 任务详情
	 */
	public $detail = '';
	
	/**
	 * @var int 任务级别，默认 普通
	 */
	public $level = Task::LEVEL_NORMAL;
	
	/**
	 * @var int 周期，默认 不重复
	 */
	public $repeat = Task::REPEAT_NO;
	
	/**
	 * @var int 排序
	 */
	public $order = 0;
	
	/**
	 * @var int 任务分类ID
	 */
	public $taskCategoryId = 0;
	
	/**
	 * @var array 负责人ID集合
	 */
	public $workerIds = '';
	
	/**
	 * @var array 相关人员ID集合
	 */
	public $relatedMemberIds = '';
	
	/**
	 * @var array 负责人集合
	 */
	public $workers = '';
	
	/**
	 * @var array 相关人员集合
	 */
	public $relatedMembers = '';
	
	/**
	 * @var string 限制完成时间
	 */
	public $limitTime = '';
	
	/**
	 * @var Worker 工作者
	 */
	public $worker = null;
	
	/**
	 * @var Task 任务
	 */
	protected $_task = null;
	
	/**
	 * @var TaskCategory 任务分类
	 */
	protected $_taskCategory = null;
	
	
	/**
	 * @inheritedoc
	 * @author KK
	 */
	public function rules(){
		return [
			[['title', 'workerIds', 'taskId'], 'required'],
			['title', 'string', 'length' => [4, 30], 'message' => '任务标题在4到30个字之间'],
			['detail', 'string', 'length' => [4, 65535], 'message' => '任务详情在4到65535个字之间'],
			[['level', 'repeat', 'order'], 'integer'],
			['order', 'compare', 'compareValue' => 0, 'operator' => '>'],
			['level', 'in', 'range' => array_keys(Task::levels())],
			['repeat', 'in', 'range' => array_keys(Task::repeats())],
			[['workerIds', 'relatedMemberIds'], 'each', 'rule' => ['integer']],
			[['workerIds', 'relatedMemberIds'], 'validateMemberIds'],
			['taskCategoryId', 'validateCategoryId'],
			['limitTime', 'validateLimitTime'],
			['taskId', 'validateTaskId'],
		];
	}
	
	/**
	 * @inheritedoc
	 * @author KK
	 */
	public function scenarios() {
		return [
			static::SCENE_ADD => ['title', 'detail', 'level', 'repeat', 'taskCategoryId', 'workerIds', 'relatedMemberIds', 'limitTime'],
			static::SCENE_LIST => ['taskCategoryId'],
			static::SCENE_INFO => ['taskId'],
			static::SCENE_MOVE => ['taskId', 'taskCategoryId', 'order'],
		];
	}
	
	/**
	 * 验证成员ID集
	 * @author KK
	 * @param string $attributeName 被验证的属性名称
	 * @param array $params 验证的附加参数
	 */
	public function validateMemberIds($attributeName, $params){
		$workers = Worker::findAll($this->{$attributeName});
		if(!$workers){
			$role = $attributeName == 'workerIds' ? '负责人' : '成员';
			$this->addError($attributeName, '无效的' . $role . 'ID');
		}else{
			$setAttr = $attributeName == 'workerIds' ? 'workers' : 'relatedMembers';
			$this->{$setAttr} = $workers;
		}
	}
	
	/**
	 * 验证任务ID
	 */
	public function validateTaskId(){
		if(!$task = Task::findOne($this->taskId)){
			return $this->addError('taskId', '无效的任务ID');
		}
		$this->_task = $task;
	}
	
	/**
	 * 验证任务分类ID
	 */
	public function validateCategoryId(){
		if(!$taskCategory = TaskCategory::findOne($this->taskCategoryId)){
			return $this->addError('taskCategoryId', '无效的任务分类ID');
		}
		$this->_taskCategory = $taskCategory;
	}
	
	/**
	 * 验证限制完成时间
	 */
	public function validateLimitTime(){
		$time = strtotime($this->limitTime);
		if($time < time()){
			return $this->addError('limitTime', '完成时间必须是未来的时间点');
		}
		$this->limitTime = date('Y-m-d H:i', $time);
	}
	
	
	
	/**
	 * 获取任务列表
	 * @author KK
	 * @return Task
	 * @throws \yii\base\ErrorException
	 * @test \xoa_test\home\unit\TaskTest::testAdd
	 */
	public function add(){
		if(!$this->validate()){
			return null;
		}
		
		$task = new Task([
			'project_id' => $this->_taskCategory->project->id,
			'task_category_id' => $this->_taskCategory->id,
			'title' => $this->title,
			'detail' => $this->detail,
			'level' => $this->level,
			'creater_id' => $this->worker->id,
			'worker_ids' => implode(',', $this->workerIds),
			'limit_time' => $this->limitTime,
			'add_time' => date('Y-m-d H:i:'),
		]);
		if($this->relatedMemberIds){
			$task->related_member_ids = implode(',', $this->relatedMemberIds);
		}
		
		if($task->save()){
			return $this->_buildTaskDescInfo($task);
		}else{
			throw new \yii\base\ErrorException('添加任务失败');
		}
	}
	
	/**
	 * 获取任务列表
	 * @author KK
	 * @return array
	 * @test \xoa_test\home\unit\TaskTest::testList
	 */
	public function getList(){
		if(!$this->validate()){
			return false;
		}
		
		$tasks = Task::findAll([
			'project_id' => $this->_taskCategory->project->id,
			'task_category_id' => $this->_taskCategory->id,
		]);
		$result = [];
		foreach($tasks as $task){
			$result[] = $this->_buildTaskDescInfo($task);
		}
		return $result;
	}
	
	/**
	 * 获取任务信息
	 * @author KK
	 * @return array
	 * @test \xoa_test\home\unit\TaskTest::testInfo
	 */
	public function getInfo() {
		if(!$this->validate()){
			return false;
		}
		
		return $this->_task->toArray(['id', 'title', 'detail', 'level', 'repeat', 'is_finish', 'limit_time', 'end_time', 'add_time', 'history']);
	}
	
	/**
	 * 移动任务到指定分类
	 * @author KK
	 * @return Task 移动后的任务实例
	 * @test \xoa_test\home\unit\TaskTest::testMove
	 */
	public function moveTask(){
		if(!$this->validate()){
			return false;
		}
		
		if(!$this->_task->isAllowModify($this->worker)){
			$this->addError('worker', '您无法移动该任务');
			return false;
		}
		
		$this->_task->orderTo($this->order);
		$this->_task->category = $this->_taskCategory;
		return $this->_task;
	}
	
	/**
	 * 构造任务简要信息
	 * @author KK
	 * @param Task $task 任务模型
	 * @return array
	 * @test \xoa_test\home\unit\TaskTest::testAdd
	 */
	protected function _buildTaskDescInfo(Task $task){
		$taskInfo = $task->toArray(['id', 'title', 'limit_time']);
		$taskInfo['workers'] = [];
		foreach ($task->workers as $worker) {
			$taskInfo['workers'][] = $worker->toArray(['name', 'avatar']);
		}
		return $taskInfo;
	}
}
