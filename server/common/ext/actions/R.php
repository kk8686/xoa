<?php
namespace xoa\common\ext\actions;

use Yii;

class R extends \yii\base\Action{
	/**
	 * @var string URL规则文件路径
	 */
	public $urlRulesFile = '';
	
	public function run(){
		if(substr(Yii::$app->request->pathinfo, -4) === '.htm' && Yii::$app->worker->isGuest){
			if(Yii::$app->request->get('rememberThisPage', 1)){
				Yii::$app->worker->returnUrl = \yii\helpers\Url::to('');
			}
			return $this->controller->redirect(Yii::$app->worker->loginUrl);
		}
		
		$htmlFile = Yii::getAlias('@webPath/') . Yii::$app->request->pathInfo . 'l';
		if(file_exists($htmlFile)){
			return $this->controller->renderFile($htmlFile);
		}
		
		$urlToPageMap = json_decode(file_get_contents($this->urlRulesFile), true);
		//匹配要显示的文件
		$ruleFile = '';
		
		$rule = Yii::createObject([
			'class' => 'yii\web\UrlRule',
			'route' => 'site/r',
			'pattern' => '<page:.+>.htm<xx:l{0,1}>',
		]);
		//debug($rule->parseRequest(Yii::$app->urlManager, Yii::$app->request), 11);
		
		foreach($urlToPageMap as $rulePattern => $templateFile){
			if(substr($rulePattern, -5) === '.html'){
				//兼容HTML
				$rulePattern = substr($rulePattern, 0, -1) . '<xx:l{0,1}>';
			}
			
			$rule = Yii::createObject([
				'class' => 'yii\web\UrlRule',
				'route' => 'site/r',
				'pattern' => $rulePattern,
			]);
			if($a=$rule->parseRequest(Yii::$app->urlManager, Yii::$app->request)){
				$ruleFile = $templateFile;
				break;
			}
			debug([$a, $rulePattern], 0);
		}

		if(!$ruleFile){
			throw new \yii\web\NotFoundHttpException('找不到该页面');
		}

		return $this->controller->renderFile('@webPath/' . $ruleFile);
	}
}