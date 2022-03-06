<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "rights".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $is_admin
 */
class Rights extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'rights';
    }

    public static function isAdmin($id): bool
    {
        $user = self::find()->select('is_admin')->where(['=', 'user_id', $id])->one();
        if ($user) {
            return $user['is_admin'] === 1;
        }
        return false;
    }

    public static function getIsAdminWithToken($token): bool
    {
        $id = Users::getUserIdWithToken($token);
        if ($id !== null) {
            if (self::find()->select('is_admin')->where(['=', 'user_id', $id['id']])->one()) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'is_admin'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'is_admin' => 'Is Admin',
        ];
    }
}
