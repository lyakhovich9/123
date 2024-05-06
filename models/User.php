<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $password
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $fio
 * @property int|null $role_id
 *
 * @property Report[] $reports
 * @property Role $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id'], 'integer'],
            [['login', 'password', 'email', 'phone', 'fio'], 'required', 'message' =>'Заполните поле'],
            [['email'],'email', 'message' =>'Email введен некорректно'],
            [['phone'],'string','min'=>11, 'max'=>11, 'tooShort'=>'Сликшом короткий номер', 'tooLong'=>'Слишком длинный номер'],
            [['login', 'password', 'email', 'phone', 'fio'], 'string', 'max' => 255],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин',
            'password' => 'Пароль',
            'password_confirmation' => 'Повторите пароль',
            'email' => 'Email',
            'phone' => 'Телефон',
            'fio' => 'ФИО',
        ];
    }

    /**
     * Gets query for [[Reports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    public static function login($login, $password) {
        $user = self::findOne(['login'=>$login]);
        if ($user && $user -> validatePassword($password)) {
            return $user;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return null;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function __toString()
    {
        return $this->login;
    }

    public static function getInstance(): null|User
    { 
        return Yii::$app->user->identity;
    }

    public function isAdmin(){
        return $this->role_id == Role::ADMIN_ROLE_ID;
    }
}
