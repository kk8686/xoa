<?php
namespace xoa_test\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use \PHPUnit_Framework_Assert;

class Unit extends \Codeception\Module
{
    private $_tester = null;

    public function _before(\Codeception\TestCase $tester){
        //这个$tester就是我们的测试用例那个类，初始化Helper的时候会传进来，我们把它存起来
        $this->_tester = $tester;
    }
	
	public function assertHasKeys($keys, $array){
		foreach($keys as $key){
			PHPUnit_Framework_Assert::assertArrayHasKey($key, $array);
		}
	}
	
	public function assertListHasKeys($keys, $list){
		foreach($list as $item){
			$this->assertHasKeys($keys, $item);
		}
	}
}
