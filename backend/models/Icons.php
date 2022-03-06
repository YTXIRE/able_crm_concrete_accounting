<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "icons".
 *
 * @property int $id
 * @property string $name
 * @property string $prefix
 */
class Icons extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'icons';
    }

    public static function saveIcons($data): bool
    {
        $model = new self();
        $model->prefix = $data['prefix'];
        $model->name = $data['name'];
        return $model->save();
    }

    public static function getAll($limit = 0, $offset = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()->all();
        }
        return self::find()->limit($limit)->offset($offset)->all();
    }

    public static function checkIsNotExitsId($id): bool
    {
        $type = self::find()->where(['=', 'id', $id])->one();
        if ($type) {
            return true;
        }
        return false;
    }

    public static function getAllCount()
    {
        return self::find()->count();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'prefix'], 'required'],
            [['name', 'prefix'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'prefix' => 'Prefix',
            'name' => 'Name',
        ];
    }
}
