<?php
namespace xoa\common\ext\controllers;

use Yii;
use yii\helpers\Console;
use xoa\common\ext\db\Command;

class MigrateController extends \yii\console\controllers\MigrateController{
	/**
	 * 测试密码
	 */
	const TEST_PASSWORD_HASH = '$2y$13$Is9kM4lajJZo3TwmeaprveUln.3QOZzBHwG2mVLtaH4qTSN964Cry';
	/**
	 * 测试密码密钥
	 */
	const TEST_PASSWORD_HASH_KEY = '55OgoPfqr6BKDcu7pIX0029_Qyb6R9dX';
	
	/**
	 * @var bool 是否模拟数据
	 */
	public $mockData = false;
	/**
	 * @var bool 导出到SQL的文件
	 */
	public $exportFile = '';
	
	/**
	 * @var resource 导出文件的句柄
	 */
	private $_exportFileHandle = null;
	
	public function init(){
		parent::init();
		Yii::$classMap['yii\db\sqlite\ColumnSchemaBuilder'] = Yii::getAlias('@hook-yii2/db/sqlite/ColumnSchemaBuilder.php');
	}
	
    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
		$options = parent::options($actionID);
		if($actionID === 'up'){
			$options = array_merge($options, ['mockData', 'exportFile']);
		}
        return $options;
    }
	
    /**
     * @inheritdoc
     */
	public function beforeAction($action){
		if($action->id == 'up' && $this->exportFile){
			$this->_parseExportFile();
			is_file($this->exportFile) && unlink($this->exportFile);
			if(!$this->_exportFileHandle = fopen($this->exportFile, 'w')){
				throw new \medical\std\ErrorException('创建导出文件失败：' . $this->exportFile);
			}
			Yii::$app->db->commandClass = Command::className();
			\yii\base\Event::on(Yii::$app->db->commandClass, Command::EVENT_AFTER_EXECUTE, [$this, 'dumpSql']);
		}
		return parent::beforeAction($action);
	}
	
	/**
	 * 将执行过的sql语句dump到导出文件里
	 * @param \yii\base\Event $event
	 */
	public function dumpSql($event){
		$sql = $event->sender->getRawSql();
		if(strpos($sql, '`migration`') === false){
			$sql = preg_replace('#^CREATE TABLE#', 'CREATE TABLE IF NOT EXISTS', $sql);
			fwrite($this->_exportFileHandle, "$sql;\n\n");
		}
	}
	
    /**
     * @inheritdoc
     */
	public function afterAction($action, $result){
		$this->_exportFileHandle && fclose($this->_exportFileHandle);
	}
	
	/**
	 * 解析导出文件
	 * @author KK
	 * @throws \yii\base\ErrorException 如果在db组件的dsn里无法匹配到数据库名称
	 */
	private function _parseExportFile(){
		$exportFile = $this->exportFile;
		if($exportFile === '@'){
			$result = null;
			$fileName = 'dump';
			if(Yii::$app->db->driverName != 'sqlite'
				&& !preg_match('#dbname=(.+)$#', Yii::$app->db->dsn, $result)){
				throw new \yii\base\ErrorException('匹配不到数据库名称');
			}
			$exportFile = Yii::getAlias('@tests/_data/' . ($result[1] ?? $fileName) . '.sql');
		}
		$this->exportFile = $exportFile;
	}

    /**
     * Upgrades with the specified migration class.
     * @param string $class the migration class name
     * @return boolean whether the migration is successful
     */
    protected function migrateUp($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }
        $this->stdout("*** applying $class\n", Console::FG_YELLOW);
        $start = microtime(true);
        $migration = $this->createMigration($class);
        if ($migration->up() !== false) {
			
			//!@!!! 增加mockData的支持 start
			if($this->mockData && method_exists($migration, 'mockData')){
				$migration->mockData();
			}
			//!@!!! 增加mockData的支持 end 其它是框架的代码
			
            $this->addMigrationHistory($class);
            $time = microtime(true) - $start;
            $this->stdout("*** applied $class (time: " . sprintf('%.3f', $time) . "s)\n\n", Console::FG_GREEN);

            return true;
        } else {
            $time = microtime(true) - $start;
            $this->stdout("*** failed to apply $class (time: " . sprintf('%.3f', $time) . "s)\n\n", Console::FG_RED);

            return false;
        }
    }
}