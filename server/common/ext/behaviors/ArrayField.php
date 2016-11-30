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
				$field = $separator;
				$separator = static::TYPE_JSON;
			}
			
			$value = $this->owner->getAttribute($field);
			if (!is_array($value)) {
				continue;
			}

			if (!$value) {
				$value = '';
			} else {
				if ($separator === static::TYPE_COMMA) {
					$value = implode(static::TYPE_COMMA, $value);
				}elseif ($separator === static::TYPE_JSON) {
					$value = json_encode($value);
					if (json_last_error()) {
						throw new InvalidValueException('解码JSON失败，数据：' . $value);
					}
				} elseif (is_callable($separator)) {
					//函数编码
					$value = $separator(true, $value);
				} else {
					throw new InvalidParamException('无法识别的编码类型');
				}
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
			if (!is_string($value)) {
				continue;
			}

			if ($value === '') {
				$value = [];
			} else {
				if ($separator === static::TYPE_COMMA) {
					$value = explode(static::TYPE_COMMA, $value);
				}elseif ($separator === static::TYPE_JSON) {
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
			}
			$this->owner->setAttribute($field, $value);
		}
	}

}
