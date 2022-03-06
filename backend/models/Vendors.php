<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "vendors".
 *
 * @property int $id
 * @property string $name
 * @property int|null $icon_id
 * @property int $is_archive
 */
class Vendors extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'vendors';
    }

    public static function saveVendor($data): bool
    {
        $model = new self();
        $model->name = $data['name'];
        $model->icon_id = $data['icon_id'];
        $model->is_archive = 0;
        return $model->save();
    }

    public static function getAll($limit = 0, $offset = 0, $archive = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()->orderBy(['id' => SORT_ASC])->where(['=', 'is_archive', $archive])->all();
        }
        return self::find()->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->where(['=', 'is_archive', $archive])->all();
    }

    public static function getAllCount($archive = 0)
    {
        return self::find()->where(['=', 'is_archive', $archive])->count();
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

    public static function checkIsNotExitsId($id): bool
    {
        $type = self::find()->where(['=', 'id', $id])->one();
        if ($type) {
            return true;
        }
        return false;
    }

    public static function updateVendor($data): bool
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        if ($model) {
            $model->name = $data['name'];
            $model->icon_id = $data['icon_id'];
            return $model->save();
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
            [['name'], 'required'],
            [['icon_id', 'is_archive'], 'integer'],
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
            'icon_id' => 'Icon ID',
            'is_archive' => 'Is Archive',
        ];
    }

    public function getIcon(): ActiveQuery
    {
        return $this->hasOne(Icons::class, ['id' => 'icon_id']);
    }

    public function getPayments(): ActiveQuery
    {
        return $this->hasMany(Payments::class, ['vendor_id' => 'id']);
    }
}
