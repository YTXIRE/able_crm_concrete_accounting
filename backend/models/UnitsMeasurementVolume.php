<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "units_measurement_volume".
 *
 * @property int $id
 * @property string|null $name
 */
class UnitsMeasurementVolume extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'units_measurement_volume';
    }

    public static function saveUnits($name)
    {
        $model = new self();
        $model->name = $name;
        $model->save();
    }

    public static function getAll(): array
    {
        return self::find()->all();
    }

    public static function checkUnit($id): bool
    {
        if (self::find()->where(['=', 'id', $id])->one()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
}
