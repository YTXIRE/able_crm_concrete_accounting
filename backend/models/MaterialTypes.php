<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "material_types".
 *
 * @property int $id
 * @property int $units_measurement_volume_id
 * @property string $name
 */
class MaterialTypes extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'material_types';
    }

    public static function checkIsNotExitsName($name): bool
    {
        $type = self::find()->where(['=', 'name', $name])->one();
        if ($type) {
            return true;
        }
        return false;
    }

    public static function saveType($data): bool
    {
        $model = new self();
        $model->name = $data['name'];
        $model->units_measurement_volume_id = $data['units_measurement_volume_id'];
        return $model->save();
    }

    public static function getAll($limit = 0, $offset = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()->orderBy(['id' => SORT_DESC])->all();
        }
        return self::find()->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->all();
    }

    public static function getAllCount()
    {
        return self::find()->count();
    }

    public static function updateType($data): bool
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        if ($model) {
            $model->name = $data['name'];
            $model->units_measurement_volume_id = $data['units_measurement_volume_id'];
            return $model->save();
        }
        return false;
    }

    public static function checkIsNotExitsId($id): bool
    {
        $type = self::find()->where(['=', 'id', $id])->one();
        if ($type) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'units_measurement_volume_id'], 'required'],
            [['name'], 'string'],
            [['units_measurement_volume_id'], 'integer'],
            [['name'], 'unique'],
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
            'units_measurement_volume_id' => 'Units Measurement Volume Id',
        ];
    }

    public function getUnits(): ActiveQuery
    {
        return $this->hasOne(UnitsMeasurementVolume::class, ['id' => 'units_measurement_volume_id']);
    }

    public function getPayment(): ActiveQuery
    {
        return $this->hasMany(Payments::class, ['material_type_id' => 'id']);
    }
}
