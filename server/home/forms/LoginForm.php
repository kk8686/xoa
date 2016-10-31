<?php
namespace xoa\home\forms;

use Yii;
use xoa\common\models\Worker;

/**
 * 登陆表单
 */
class LoginForm extends \yii\base\Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_worker = null;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $worker = $this->getWorker();

            if (!$worker || !$worker->validatePassword($this->password)) {
                $this->addError($attribute, '无效的帐号或密码');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->worker->login($this->getWorker(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return Worker|null
     */
    public function getWorker()
    {
        if ($this->_worker === null) {
            $this->_worker = Worker::findByEmail($this->email);
        }

        return $this->_worker;
    }
}
