<?php

namespace app\models;

use DateTime;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string|null $created_at
 * @property string|null $last_login_at
 * @property string|null $token
 */
class Users extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    public static function getAllUsers($limit = 0, $offset = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()->select(['id', 'login', 'email', 'created_at', 'last_login_at'])->orderBy(['id' => SORT_DESC])->all();
        }
        return self::find()->select(['id', 'login', 'email', 'created_at', 'last_login_at'])->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->all();
    }

    public static function getAllCount()
    {
        return self::find()->count();
    }

    public static function checkUserData($data): int
    {
        if (self::findOne(['email' => $data['email']]) || self::findOne(['login' => $data['login']])) {
            return true;
        }
        return false;
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public static function createUser($data): array
    {
        $date = new DateTime();
        $model = new self();
        $model->login = $data['login'];
        $model->email = $data['email'];
        $model->password = Yii::$app->getSecurity()->generatePasswordHash($data['password']);
        $model->created_at = $date->getTimestamp();
        $model->last_login_at = null;
        if ($model->save()) {
            return [
                'id' => $model['id'],
                'login' => $data['login'],
                'email' => $data['email'],
                'created_at' => date('d.m.Y H:i:s', $model['created_at']),
                'code' => 1
            ];
        }
        return [
            'code' => 0
        ];
    }

    public static function getUserInfo($token)
    {
        return self::find()->select(['id', 'login', 'email', 'created_at', 'last_login_at'])->where(['=', 'token', $token])->one();
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public static function deleteUser($id)
    {
        $model = self::find()->where(['=', 'id', $id])->one();
        $model->delete();
    }

    public static function updateUserInfo($data): array
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        if (array_key_exists('login', $data)) {
            $model->login = $data['login'];
        }
        if (array_key_exists('email', $data)) {
            $model->email = $data['email'];
        }
        if ($model->save()) {
            return [
                'id' => $model['id'],
                'login' => $data['login'],
                'email' => $data['email'],
                'code' => 1
            ];
        }
        return [
            'code' => 0
        ];
    }

    public static function checkExistUserWithId($id)
    {
        return self::find()->where(['=', 'id', $id])->one();
    }

    /**
     * @throws Exception
     */
    public static function changeUserPassword($data): int
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        $model->password = Yii::$app->getSecurity()->generatePasswordHash($data['password']);
        if ($model->save()) {
            return 1;
        }
        return 0;
    }

    public static function checkExistUserWithLogin($login)
    {
        return self::find()->select('password')->where(['=', 'login', $login])->one();
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public static function generateToken($data): array
    {
        $date = new DateTime();
        $token = Yii::$app->getSecurity()->generatePasswordHash($data['password'] . $date->getTimestamp());
        $model = self::find()->where(['=', 'login', $data['login']])->one();
        $model->last_login_at = $date->getTimestamp();
        $model->token = $token;
        if ($model->save()) {
            return [
                'id' => $model['id'],
                'token' => $token,
                'code' => 1
            ];
        }
        return [
            'code' => 0
        ];
    }

    public static function removeToken($token): array
    {
        $model = self::find()->where(['=', 'token', $token])->one();
        $model->token = null;
        if ($model->save()) {
            return [
                'code' => 1
            ];
        }
        return [
            'code' => 0
        ];
    }

    public static function checkExistUserWithToken($token)
    {
        return self::find()->select('password')->where(['=', 'token', $token])->one();
    }

    public static function getUserIdWithLogin($login)
    {
        return self::find()->where(['=', 'login', $login])->one();
    }

    public static function getUserIdWithToken($token)
    {
        return self::find()->where(['=', 'token', $token])->one();
    }

    public static function checkTokenUserWithLogin($login)
    {
        return self::find()->select(['id', 'token'])->where(['=', 'login', $login])->one();
    }

    public static function checkUserWithTokenAndID($data): bool
    {
        if (self::find()->where(['=', 'id', $data['id']])->andWhere(['=', 'token', $data['token']])->one()) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['login', 'email', 'password'], 'required'],
            [['created_at', 'last_login_at'], 'safe'],
            [['login', 'email', 'password'], 'string'],
            [['password'], 'string', 'min' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'email' => 'Email',
            'password' => 'Password',
            'created_at' => 'Created At',
            'last_login_at' => 'Last Login At',
            'token' => 'Token',
        ];
    }
}
