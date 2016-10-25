<?php
namespace xoa\common\ext\validators;

use Yii;

/**
 * 手机号验证器
 * @author KK
 * @since 2.0
 */
class MobileValidator extends \yii\validators\Validator
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '手机号码必须11位纯数字.');
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        $valid = true;
        if (!preg_match('/^1[0-9]{10}$/', $value)) {
            $valid = false;
        }

        return $valid ? null : [$this->message, []];
    }
}
