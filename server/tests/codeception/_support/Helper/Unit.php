<?php
namespace xoa_test\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use \PHPUnit\Framework\Assert;

class Unit extends \Codeception\Module
{
    private $_tester = null;

    public function _before(\Codeception\TestCase $tester){
        //这个$tester就是我们的测试用例那个类，初始化Helper的时候会传进来，我们把它存起来
        $this->_tester = $tester;
    }
	
	/**
	 * 断言数组包含指定的key集合
	 * @author KK
	 * @param array $keys 要断言包含的key集合
	 * @param array $array 被断言的数组
	 * @param bool $only 是否只允许包含这些，false的话则$array多出其它key也可以通过
	 * @param string $message 断言失败提示消息
	 */
	public function assertHasKeys(array $keys, array $array, bool $only = true, string $message = ''){
		foreach($keys as $key){
			Assert::assertArrayHasKey($key, $array, $message);
		}
		$only && Assert::assertEmpty(array_diff($keys, array_keys($array)), '数组有多出的key');
	}
	
	
	/**
	 * 断言阵列数组包含指定的key集合
	 * @author KK
	 * @param array $keys 要断言包含的key集合
	 * @param array $list 被断言的阵列数组
	 * @param bool $only 是否只允许包含这些，false的话则$list里的每一个数组多出其它key也可以通过
	 * @param string $message 断言失败提示消息
	 */
	public function assertListHasKeys(array $keys, array $list, bool $only = true, string $message = ''){
		foreach($list as $item){
			$this->assertHasKeys($keys, $item, $only, $message);
		}
	}
}
