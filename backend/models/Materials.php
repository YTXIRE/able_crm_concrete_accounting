<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "materials".
 *
 * @property int $id
 * @property int $type_id
 * @property string|null $name
 */
class Materials extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'materials';
    }

    public static function saveMaterial($data): bool
    {
        $model = new self();
        $model->type_id = $data['type_id'];
        $model->name = $data['name'];
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

    public static function checkIsNotExitsId($id): bool
    {
        $type = self::find()->where(['=', 'id', $id])->one();
        if ($type) {
            return true;
        }
        return false;
    }

    public static function updateType($data): bool
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        if ($model) {
            $model->type_id = $data['type_id'];
            $model->name = $data['name'];
            return $model->save();
        }
        return false;
    }

    public static function checkIsNotExitsName($name): bool
    {
        $type = self::find()->where(['=', 'name', $name])->one();
        if ($type) {
            return true;
        }
        return false;
    }

    public static function getMaterialsByType($type_id): array
    {
        return self::find()->where(['=', 'type_id', $type_id])->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type_id'], 'required'],
            [['type_id'], 'integer'],
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
            'type_id' => 'Type ID',
            'name' => 'Name',
        ];
    }

    public function getMaterialType(): ActiveQuery
    {
        return $this->hasOne(MaterialTypes::class, ['id' => 'type_id']);
    }
}
