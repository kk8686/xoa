<?php
namespace xoa\console\commands;

use Yii;
use ReflectionClass;
use yii\base\Model;
use xoa\common\models\{
	Task,
	Project,
	Worker
};

/**
 * 构建控制器
 * @author KK
 */
class BuildController extends \yii\console\Controller{
	/**
	 * 生成字典文件路径
	 */
	const DICT_FILE = PROJECT_PATH . '/common/dict.js';
	
	/**
	 * 构建字典
	 * 输出为JS模块文件，主要是给前端构建用的
	 * @author KK
	 */
	public function actionDict(){
		$dict = [];
		$buildModelFiles = $this->_collectAllFiles(Yii::getAlias('@xoa/common/models'));
		$buildFormFiles =$this->_collectAllFiles(Yii::getAlias('@xoa/common/forms'));
		
		foreach([
			Task::className(),
			Project::className(),
			Worker::className(),
		] as $class){
			$nodeName = $this->_getNodeName($class);
			$this->stdout('building ' . $class . ' to ' . $nodeName . ' ...');
			
			$classReflection = new ReflectionClass($class);
			$dict[$nodeName] = $this->_getClassConstants($classReflection);
			if($classReflection->isSubclassOf(Model::className())){
				$dict[$nodeName]['labels'] = (new $class())->getAttributes();
			}
			
			foreach($classReflection->getMethods() as $method){
				if(preg_match('#\s+\* @labels#', $method->getDocComment())){
					$dict[$nodeName]['labels'][$method->getName()] = $method->invoke($classReflection->newInstanceArgs());
				}
			}
			$this->stdout(' done' . PHP_EOL);
		}
		
		$dictJson = json_encode($dict, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_UNICODE);
		$result = [];
		foreach(explode("\n", $dictJson) as $line){
			$line = preg_replace('# {4}#', "\t", $line);
			$result[] = preg_replace('#^(\s+)"(\w+)":#', '$1$2 :', $line);
		}
		file_put_contents(static::DICT_FILE, 'module.exports = ' . implode(PHP_EOL, $result) . ';');
	}
	
	/**
	 * 获取节点名称
	 * @param string $className 类名
	 * @return string
	 */
	protected function _getNodeName(string $className){
		$nodeName = '';
		foreach(explode('\\', substr($className, 4)) as $i => $namespaceItem){
			if($i == 0){
				$nodeName .= $namespaceItem;
			}else{
				$nodeName .= ucfirst($namespaceItem);
			}
		}
		//$nodeName = str_replace('\\', '.', substr($class, 4));
		return $nodeName;
	}
	
	/**
	 * 获取类的常量集合，只是这个类，不包含哦
	 * @author KK
	 * @param ReflectionClass $classReflection
	 * @return array
	 */
	protected function _getClassConstants(ReflectionClass $classReflection){
		$parentConstants = $classReflection->getParentClass()->getConstants();
		$classConstants = [];
		foreach($classReflection->getConstants() as $name => $value){
			if(!array_key_exists($name, $parentConstants) || $value !== $parentConstants[$name]){
				$classConstants[$name] = $value;
			}
		}
		return $classConstants;
	}
	
	/**
	 * 收集所有文件
	 * @param string $path 要收集的目录
	 * @return array
	 */
	protected function _collectAllFiles($path){
		$files = [];
		foreach(scandir($path) as $file){
			if($file == '.' || $file == '..'){
				continue;
			}
			$fullFile = $path . PATH_SEPARATOR . $file;
			$files[] = $path . PATH_SEPARATOR . $file;
			if(is_dir($fullFile)){
				$files = array_merge($files, $this->_collectAllModelFiles($fullFile));
			}
		}
		return $files;
	}
}
