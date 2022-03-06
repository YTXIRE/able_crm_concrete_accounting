<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "legal_entities_types".
 *
 * @property int $id
 * @property string $name
 * @property string $full_name
 */
class LegalEntitiesTypes extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'legal_entities_types';
    }

    public static function saveType($data): bool
    {
        $model = new self();
        $model->name = $data['name'];
        $model->full_name = $data['full_name'];
        return $model->save();
    }

    public static function getAll(): array
    {
        return self::find()->all();
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
    public function rules(): array
    {
        return [
            [['name', 'full_name'], 'required'],
            [['name', 'full_name'], 'string'],
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
            'full_name' => 'Full Name',
        ];
    }
}
