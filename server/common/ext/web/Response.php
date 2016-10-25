<?php
namespace xoa\common\ext\web;

/**
 * 响应
 * @author KK
 */
class Response extends \yii\web\Response{
    public function __construct(string $message = '', int $code = 1, $data = null){
        $this->data = static::makeArray($message, $code, $data);
        $this->format = parent::FORMAT_JSON;
		parent::__construct();
    }
	
	/**
	 * 创造数组结构的响应报文
	 * @param string $message 响应的消息
	 * @param int $code 响应的代码
	 * @param mixed $data 附加数据
	 * @return array
	 */
	public static function makeArray(string $message = '', int $code = 1, $data = null){
		return [
			'message' => $message,
			'code' => $code,
			'data' => $data,
		];
	}
}