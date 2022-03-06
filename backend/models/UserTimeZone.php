<?php

namespace app\models;

use Throwable;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "user_timezone".
 *
 * @property int $timezone_id
 * @property int $user_id
 */
class UserTimeZone extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user_timezone';
    }

    public static function primaryKey(): array
    {
        return [
            'timezone_id',
            'user_id'
        ];
    }

    public static function saveTimeZone($timezone_id, $user_id): bool
    {
        $model = self::find()->where(['=', 'user_id', $user_id])->one();
        if ($model === null) {
            $model = new self();
            $model->timezone_id = $timezone_id;
            $model->user_id = $user_id;
        } else {
            $model->timezone_id = $timezone_id;
        }
        return $model->save();
    }

    public static function getUserTimezone($user_id)
    {
        $record = self::find()->where(['=', 'user_id', $user_id])->one();
        return TimeZone::getTimezoneNameById($record->timezone_id);
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public static function deleteTimeZone($user_id)
    {
        $model = self::find()->where(['=', 'user_id', $user_id])->one();
        $model->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['timezone_id', 'user_id'], 'required'],
            [['timezone_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'timezone_id' => 'Timezone ID',
            'user_id' => 'User ID',
        ];
    }
}
