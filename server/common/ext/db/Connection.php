<?php
namespace xoa\common\ext\db;

use Yii;
use yii\log\Logger;

/**
 * @inheritdoc
 */
class Connection extends \yii\db\Connection{
	/**
	 * 获取上一条执行的SQL语句
	 * @param int $nums 要获取的条数
	 * @param string $patten 搜索表达式,可以为普通字符串搜索,或者以#或/作定界符的正则表达式搜索
	 * @return array 匹配的SQL语句列表
	 * @author KK
	 * @test \xoa_test\home\unit\yii_ext\ConnectionTest::testGetLastSqls
	 */
	public function getLastSqls($nums = 1, $patten = ''){
		$messageList = &Yii::getLogger()->messages;

		$sqlList = [];	//匹配结果
		$matchIndex = -1;
		//遍历系统消息
		for($i = count($messageList) - 1; $i >=0; $i--){
			$messageLevel = $messageList[$i][1];
			$messageCategory = $messageList[$i][2];
			if($messageLevel != Logger::LEVEL_INFO
				|| !in_array($messageCategory, [
					'yii\db\Command::query',
					'yii\db\Command::execute',
				])){
				//如果不是执行SQL的消息记录就跳过
				continue;
			}

			$sql = $messageList[$i][0];
			if(	strpos($sql, 'SHOW CREATE TABLE') === 0
				|| strpos($sql, 'SHOW FULL COLUMNS FROM') === 0
				){
				continue;
			}

			$matched = false;	//是否匹配
			if(!$patten){
				//无搜索
				$matched = true;
			}else{
				if($patten[0] != '#'){
					//字符搜索
					if(strpos($sql, $patten) !== false){
						$matched = true;
					}
				}elseif($patten[0] == '#' || $patten[0] == '/'){
					//正则搜索
					$matchResult = [];
					if(preg_match($patten, $sql, $matchResult)){
						$matched = true;
					}
				}
			}

			if($matched){
				$sqlList[++$matchIndex] = $sql;
				if($matchIndex + 1 == $nums){
					//匹配足够
					break;
				}
			}

		}

		return $sqlList;
	}
}