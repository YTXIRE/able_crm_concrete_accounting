<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property int|null $active
 */
class Settings extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['active'], 'integer'],
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
            'active' => 'Active',
        ];
    }

    public static function createSettings($data)
    {
        $model = new self();
        $model->name = $data['name'];
        $model->active = $data['active'];
        $model->save();
    }

    public static function getSettings($name): bool
    {
        $model = self::find()->where(['=', 'name', $name])->one();
        return $model['active'] === 1;
    }

    public static function setSettings($data): bool
    {
        $model = self::find()->where(['=', 'name', $data['name']])->one();
        $model->active = $data['active'];
        return $model->save();
    }
}
