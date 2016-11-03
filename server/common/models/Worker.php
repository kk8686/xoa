<?php
namespace xoa\common\models;

use Yii;

/**
 * 工作者
 * @author KK
 */
class Worker extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
	/**
	 * 性别：男
	 */
	const GENDER_MALE = 1;
	/**
	 * 性别：女
	 */
	const GENDER_FEMALE = 2;
	
    public $authKey;
    public $accessToken;

	public static function tableName(){
		return 'worker';
	}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		throw new \Exception('findIdentityByAccessToken 未实现');
        return null;
    }

    /**
     * Finds user by email
     * @param string $mail
     * @return static|null
     */
    public static function findByEmail($mail)
    {
        return self::findOne(['email' => $mail]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
		header('xx2:'.$password);
        return Yii::$app->security->validatePassword($password . $this->hash_key, $this->password_hash);
    }
	
	public function fields(){
		$fields = parent::fields();
		unset($fields['password_hash'], $fields['password_hash_key']);
		return $fields;
	}
	
	/**
	 * 根据明文密码生成加密的密码信息
	 * @author KK
	 * @param string $password 明文密码
	 * @return array 包含
	 */
	public static function generatePassword(string $password){
		$passwordHashKey = Yii::$app->security->generateRandomString();
		$passwordHash = Yii::$app->security->generatePasswordHash($password . $passwordHashKey);
		return [
			'password_hash' => $passwordHash,
			'hash_key' => $passwordHashKey,
		];
	}
}
