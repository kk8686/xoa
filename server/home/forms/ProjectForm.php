<?php
namespace xoa\home\forms;

use yii\db\Query;
use xoa\common\models\{
	Project,
	Worker
};

/**
 * 项目表单
 * @author KK
 */
class ProjectForm extends \yii\base\Model{
	const SCENE_ADD = 'add';
	/**
	 * @var string 项目名称
	 */
	public $name = '';
	
	/**
	 * @var Worker 创建者
	 */
	public $creater = null;
	
	public function rules(){
		return [
			[['name', 'creater'], 'required'],
			['name', 'string', 'length' => [1, 30]],
			['name', 'validateSameName']
		];
	}
	
	public function scenarios() {
		return [
			static::SCENE_ADD => ['name', 'creater'],
		];
	}
	
	/**
	 * 检查当前用户是否创建过相同名字的项目名称
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
	 * 添加项目
	 * @author KK
	 * @return Project
	 * @throws \yii\base\ErrorException
	 * @test \xoa_test\home\unit\ProjectTest
	 */
	public function add() : Project{
		if(!$this->validate()){
			return null;
		}
		
		$project = new Project([
			'name' => $this->name,
			'worker_id' => $this->creater->id,
			'add_time' => date('Y-m-d'),
		]);
		if(!$project->save()){
			throw new \yii\base\ErrorException('添加项目失败');
		}
		return $project;
	}
}