<?php
use yii\helpers\ArrayHelper;

class AppBooter{
	/**
	 * 开发环境IP
	 */
	const DEV_IP = '127.0.0.1';
	/**
	 * 测试环境IP
	 */
	const TEST_IP = '192.168.1.172';
	
	/**
	 * @event 加载完配置
	 */
	const EVENT_AFTER_LOAD_CONFIG = 'after-load-config';
	
	/**
	 * @var string 框架目录
	 */
	public static $vendorPath = '';
	
	/**
	 * @var string 域名前缀
	 */
	public static $domainPrefix = '';
	
	/**
	 * @var array 事件列表
	 */
	protected static $_events = [];
	
	/**
	 * @var bool 是否初始化过
	 */
	private static $_inited = false;
	/**
	 * @var string 环境名称：dev|test|prod
	 */
	public static $env = '';

	/**
	 * 批量设置APP管理器的属性配置方法
	 * @author KK
	 * @param array $configs
	 */
	public static function config(array $configs = []){
		foreach($configs as $configName => $configValue){
			static::$configName = $configValue;
		}
	}
	
	public static function autoSetEnv(){
		if(!isset($_SERVER['SERVER_ADDR'])){
			static::$env = 'dev'; //本机开发环境
		}elseif($_SERVER['SERVER_ADDR'] == static::DEV_IP){
			static::$env = 'dev'; //本机开发环境
		}elseif($_SERVER['SERVER_ADDR'] == static::TEST_IP){
			static::$env = 'test'; //测试环境
		}else{
			static::$env = 'prod'; //线上生产环境
		}
	}
	
	/**
	 * 初始化
	 * @author KK
	 */
	public static function init(){
		if(static::$_inited){
			return;
		}
		if(!static::$env){
			static::autoSetEnv();
		}
		
		if(!static::$vendorPath){
			if(!static::$vendorPath = realpath(__DIR__ . '/../framework')){
				throw new \ErrorException('无效的框架目录: ' . __DIR__ . '/../framework');
			}
		}
		
		if(static::$env != 'prod'){
			static::$domainPrefix = static::$env;
		}
		
		static::_importYii2() && static::$_inited = true;
	}
	
	/**
	 * 引入yii2
	 * @author KK
	 */
	protected static function _importYii2(){
		defined('YII_DEBUG') or define('YII_DEBUG', static::$env != 'prod');
		defined('YII_ENV') or define('YII_ENV', static::$env);
		defined('YII_ENV') or define('YII_ENV', static::$env);
		require(__DIR__ . '/debug.php');
		require(static::$vendorPath . '/autoload.php');
		require(static::$vendorPath . '/yiisoft/yii2/Yii.php');
		return class_exists('Yii');
	}
	
	/**
	 * 创建APP
	 * @author KK
	 * @param string $appPath
	 * @return \yii\web\Application|\yii\console\Application
	 */
	public static function createApp(string $appPath, array $specifyConfig = [], bool $isConsoleApp = false){
		!static::$_inited && static::init();
		
		$configPath = $appPath . '/config';
		$appConfigFile = $configPath . '/config.php';
		$commonConfigFile = PROJECT_PATH . '/server/common/config/common.php';
		$appLocalConfigFile = $configPath . '/config-local.php';
		$config = ArrayHelper::merge(
			include($appConfigFile),
			include($commonConfigFile),
			$specifyConfig,
			include($appLocalConfigFile)
		);
		
		static::trigger(static::EVENT_AFTER_LOAD_CONFIG, ['config' => $config]);
		
		if($isConsoleApp){
			return new \yii\console\Application($config);
		}else{
			return new \yii\web\Application($config);
		}
	}
	
	/**
	 * 监听事件
	 * @author KK
	 * @param string $event 事件名称
	 * @param callable $callback 事件回调
	 */
	public static function on(string $event, callable $callback) : void{
		if(!isset(static::$_events[$event])){
			static::$_events[$event] = [];
		}
		static::$_events[$event][] = $callback;
	}
	
	/**
	 * 触发事件
	 * @author KK
	 * @param string $event 事件名称
	 * @param mixed $data 相关数据
	 * @return int 触发回调的数量
	 */
	public static function trigger(string $event, $data) : int{
		if(!isset(static::$_events[$event])){
			return 0;
		}
		
		foreach(static::$_events[$event] as $callback){
			call_user_func_array($callback, $data);
		}
		return count(static::$_events[$event]);
	}
}