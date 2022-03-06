<?php

namespace app\models;

use DateTime;
use Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "legal_entities".
 *
 * @property int $id
 * @property string $legal_entities_type_id
 * @property string $name
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $is_archive
 */
class LegalEntities extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'legal_entities';
    }

    /**
     * @throws Exception
     */
    public static function saveLegalEntities($data): bool
    {
        $date = new DateTime();
        $model = new self();
        $model->name = $data['name'];
        $model->legal_entities_type_id = (string)$data['legal_entities_type_id'];
        $model->created_at = $date->getTimestamp();
        $model->is_archive = 0;
        return $model->save();
    }

    public static function getAll($limit = 0, $offset = 0, $archive = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()->orderBy(['id' => SORT_DESC])->where(['=', 'is_archive', $archive])->all();
        }
        return self::find()->select(['id', 'name', 'legal_entities_type_id'])->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->where(['=', 'is_archive', $archive])->all();
    }

    public static function getAllCount($archive = 0)
    {
        return self::find()->where(['=', 'is_archive', $archive])->count();
    }

    public static function getLegalEntity($id): array
    {
        return self::find()->select(['id', 'name', 'legal_entities_type_id'])->where(['=', 'id', $id])->all();
    }

    /**
     * @throws Exception
     */
    public static function updateLegalEntities($data): bool
    {
        $date = new DateTime();
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        if ($model) {
            $model->name = $data['name'];
            $model->legal_entities_type_id = $data['legal_entities_type_id'];
            $model->updated_at = $date->getTimestamp();
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
            [['legal_entities_type_id', 'name'], 'required'],
            [['created_at', 'updated_at', 'legal_entities_type_id', 'is_archive'], 'integer'],
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
            'legal_entities_type_id' => 'Legal Entities Type ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_archive' => 'Is Archive',
        ];
    }

    public function getType(): ActiveQuery
    {
        return $this->hasOne(LegalEntitiesTypes::class, ['id' => 'legal_entities_type_id']);
    }
}
