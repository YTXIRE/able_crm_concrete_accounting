<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "units_measurement_volume".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_name
 * @property string|null $full_name
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

    public static function saveUnits($value, $fullName = null): bool
    {
        $model = new self();
        $columns = self::getTableSchema()->columns;

        if (isset($columns['name'])) {
            $model->name = $value;
        }

        if (isset($columns['short_name'], $columns['full_name'])) {
            $model->short_name = $fullName === null ? $value : $value;
            $model->full_name = $fullName === null ? $value : $fullName;
        }

        return $model->save(false);
    }

    public static function getAll(): array
    {
        return self::find()->orderBy(['id' => SORT_ASC])->all();
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
            [['name', 'short_name', 'full_name'], 'string'],
        ];
    }

    public function asApiArray(): array
    {
        return [
            'id' => (int)$this['id'],
            'short_name' => $this['short_name'],
            'full_name' => $this['full_name'],
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
