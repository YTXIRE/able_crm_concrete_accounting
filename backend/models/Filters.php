<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "filters".
 *
 * @property int $id
 * @property string $name
 * @property string|null $filter
 */
class Filters extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'filters';
    }

    public static function saveFilters($data): bool
    {
        $model = new self();
        $model->filter = json_encode($data['filters']);
        $model->name = $data['name'];
        return $model->save();
    }

    public static function getAll(): array
    {
        return self::find()->orderBy(['id' => SORT_ASC])->all();
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
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public static function deleteFilter($id)
    {
        $model = self::find()->where(['=', 'id', $id])->one();
        return $model->delete();
    }

    public static function updateFilters($data): bool
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        $model->name = $data['name'];
        $model->filter = json_encode($data['filters']);
        return $model->save();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['filter', 'name'], 'string'],
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
            'filter' => 'Filter',
        ];
    }
}
