<?php
namespace xoa_test\_support\models;

use xoa\common\ext\behaviors\ArrayField;

class ArrayFieldModel extends \yii\db\ActiveRecord{
	public static function tableName(){
		return '_test_array_field';
	}
	
	public function behaviors(){
		return [
			[
				'class' => ArrayField::className(),
				'fields' => [
					'user_ids' => ArrayField::TYPE_COMMA,
					'history'
				],
			],
		];
	}
}