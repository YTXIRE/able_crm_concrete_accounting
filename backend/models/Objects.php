<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "objects".
 *
 * @property int $id
 * @property string $name
 * @property int $is_archive
 */
class Objects extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'objects';
    }

    public static function saveObject($data): bool
    {
        $model = new self();
        $model->name = $data['name'];
        $model->is_archive = 0;
        return $model->save();
    }

    public static function getAll($limit = 0, $offset = 0, $archive = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()->orderBy(['id' => SORT_DESC])->where(['=', 'is_archive', $archive])->all();
        }
        return self::find()->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->where(['=', 'is_archive', $archive])->all();
    }

    public static function getAllCount($archive = 0)
    {
        return self::find()->where(['=', 'is_archive', $archive])->count();
    }

    public static function updateObject($data): bool
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        if ($model) {
            $model->name = $data['name'];
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

    public static function setIsArchive($id, $status): bool
    {
        $model = self::find()->where(['=', 'id', $id])->one();
        if ($model) {
            $model->is_archive = $status;
            return $model->save();
        }
        return false;
    }

    public static function checkDuplicate($name): bool
    {
        $model = self::find()->where(['=', 'name', $name])->one();
        if ($model) {
            return true;
        }
        return false;
    }

    public static function checkIsArchive($id): bool
    {
        $model = self::find()->where(['=', 'id', $id])->one();
        if ($model->is_archive === 1) {
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
            [['is_archive'], 'integer'],
            [['name'], 'required'],
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
            'is_archive' => 'Is Archive',
        ];
    }
}
