<?php

namespace xoa\common\ext\behaviors;

use yii\base\Behavior;
use yii\base\InvalidParamException;
use yii\base\InvalidValueException;
use yii\db\ActiveRecord;

/**
 * 数组字段
 * @author KK
 * @test \xoa_test\unit\yii_ext\ArrayFieldTest
 */
class ArrayField extends Behavior {

	/**
	 * 类型：逗与分隔
	 */
	const TYPE_COMMA = ',';

	/**
	 * 类型：JSON
	 */
	const TYPE_JSON = 'json';

	/**
	 * 类型：前缀
	 */
	const TYPE_PREFIX = 'prefix';

	/**
	 * 类型： 小数
	 */
	const TYPE_DECIMAL = '.';

	/**
	 * @var 要编码的字段
	 */
	public $fields = [];

	/**
	 * @inheritdoc
	 */
	public function events() {
		return [
			ActiveRecord::EVENT_AFTER_FIND => function () {
				$this->decode();
			},
			ActiveRecord::EVENT_BEFORE_INSERT => function () {
				$this->encode();
			},
			ActiveRecord::EVENT_BEFORE_UPDATE => function () {
				$this->encode();
			},
			ActiveRecord::EVENT_AFTER_INSERT => function () {
				$this->decode();
			},
			ActiveRecord::EVENT_AFTER_UPDATE => function () {
				$this->decode();
			},
		];
	}

	/**
	 * 编码
	 * @author KK
	 */
	protected function encode() {
		foreach ($this->fields as $field => $separator) {
			if (is_int($field)) {
				//如果fields元素没有定义key则默认为json
				$field = $separator;
				$separator = static::TYPE_JSON;
			}
			
			$value = $this->owner->getAttribute($field);

			if (!$value) {
				$value = '';
				$this->owner->setAttribute($field, $value);
				continue;
			}
				
			if ($separator === static::TYPE_COMMA) {
				if (!is_array($value)) {
					continue;
				}

				$value = implode(static::TYPE_COMMA, $value);
			}elseif ($separator === static::TYPE_JSON) {
				if (!is_array($value)) {
					continue;
				}

				$value = json_encode($value);
				if (json_last_error()) {
					throw new InvalidValueException('解码JSON失败，数据：' . $value);
				}
			}elseif ($separator === static::TYPE_PREFIX) {
				if(!is_string($value)){
					throw new \yii\base\InvalidParamException($field . '字段 编码方式：' . static::TYPE_PREFIX . '出错，无效的被编码值，预期应该是字符串');
				}
				
				if ($value[0] === $separator) {
					continue;
				}

				$value = $separator . $value;
			} elseif (is_callable($separator)) {
				//函数编码
				$value = $separator(true, $value);
			} else {
				throw new InvalidParamException('无法识别的编码类型');
			}
			$this->owner->setAttribute($field, $value);
		}
	}

	/**
	 * 解码
	 * @author KK
	 */
	protected function decode() {
		foreach ($this->fields as $field => $separator) {
			if (is_int($field)) {
				$field = $separator;
				$separator = static::TYPE_JSON;
			}
			
			$value = $this->owner->getAttribute($field);
			if ($value === '') {
				$value = [];
				$this->owner->setAttribute($field, $value);
				continue;
			}
			
			if ($separator === static::TYPE_COMMA) {
				if (!is_string($value)) {
					continue;
				}

				$value = explode(static::TYPE_COMMA, $value);
				
			}elseif ($separator === static::TYPE_JSON) {
				if (!is_string($value)) {
					continue;
				}

				$value = json_decode($value, true);
				if (json_last_error()) {
					throw new InvalidValueException('解码JSON失败，数据：' . $value);
				}
			} elseif (is_callable($separator)) {
				//函数解码
				$value = $separator(false, $value);
			} else {
				throw new InvalidParamException('无法识别的编码类型');
			}
			$this->owner->setAttribute($field, $value);
		}
	}

}
