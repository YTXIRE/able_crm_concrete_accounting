<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "timezone".
 *
 * @property int $id
 * @property string $timezone_name
 */
class TimeZone extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'timezone';
    }

    public static function saveTimeZone($timezone)
    {
        $model = new self();
        $model->timezone_name = $timezone;
        $model->save();
    }

    public static function getTimezones(): array
    {
        return self::find()->all();
    }

    public static function checkExistsTimezone($timezone_id)
    {
        return self::find()->where(['=', 'id', $timezone_id])->one();
    }

    public static function getTimezoneNameById($timezone_id)
    {
        return self::find()->where(['=', 'id', $timezone_id])->one()['timezone_name'];
    }

    public static function getTimezoneIdWithName($timezone)
    {
        return self::find()->where(['=', 'timezone_name', $timezone])->one()['id'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['timezone_name'], 'required'],
            [['timezone_name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'timezone_name' => 'Timezone Name',
        ];
    }
}
