<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "clndr_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $surname
 * @property string $password
 * @property string $salt
 * @property string $access_token
 * @property string $create_date
 *
 * @property ClndrAccess[] $clndrAccesses
 * @property ClndrAccess[] $clndrAccesses0
 * @property ClndrCalendar[] $clndrCalendars
 */
class User extends ActiveRecord implements IdentityInterface
{
    /*
     * Константы для проверок
     */

    const PASS_MIN_LENGTH = 8;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clndr_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'name', 'surname', 'password', 'salt'], 'required'],
            [['create_date'], 'safe'],
            [['username'], 'string', 'max' => 45],
            [['name', 'surname'], 'string', 'max' => 45],
            [['password', 'salt', 'access_token'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => User::PASS_MIN_LENGTH],
            [['username'], 'unique'],
            [['access_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Логин'),
            'name' => Yii::t('app', 'Имя'),
            'surname' => Yii::t('app', 'Фамилия'),
            'password' => Yii::t('app', 'Пароль'),
            'salt' => Yii::t('app', 'Salt'),
            'access_token' => Yii::t('app', 'Access Token'),
            'create_date' => Yii::t('app', 'Дата создания'),
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getAuthKey()
    {
        return $this->access_token;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function ValidatePassword($password)
    {
        return $this->password === $this->passWithSalt($password, $this->salt);
    }

    public function passWithSalt ($password, $salt)
    {
        return hash("sha512", $password . $salt);
    }

    public function saltGenerator ()
    {
        return hash("sha512", uniqid('salt_', true));
    }

    public function beforeSave ($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->getIsNewRecord() && !empty($this->password))
            {
                $this->salt = $this->saltGenerator();
            }
            if (!empty($this->password))
            {
                $this->password = $this->passWithSalt($this->password, $this->salt);
            }
            else
            {
                unset($this->password);
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClndrAccesses()
    {
        return $this->hasMany(ClndrAccess::className(), ['user_owner' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClndrAccesses0()
    {
        return $this->hasMany(ClndrAccess::className(), ['user_guest' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClndrCalendars()
    {
        return $this->hasMany(ClndrCalendar::className(), ['creator' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\query\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\UserQuery(get_called_class());
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

}
