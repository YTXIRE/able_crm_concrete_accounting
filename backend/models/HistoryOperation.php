<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "history_operation".
 *
 * @property int $id
 * @property int|null $vendor_id
 * @property int|null $is_debt
 * @property int|null $material_id
 * @property int|null $legal_entity_id
 * @property int|null $object_id
 * @property float|null $volume
 * @property float|null $price
 * @property float|null $total
 * @property int|null $file_id
 * @property string|null $comment
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $confirmed_data
 */
class HistoryOperation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'history_operation';
    }

    public static function getAll($vendor_id, $material_id, $object_id, $limit = 0, $offset = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()
                ->where(['=', 'vendor_id', $vendor_id])
                ->andWhere(['=', 'material_id', $material_id])
                ->andWhere(['=', 'object_id', $object_id])
                ->orderBy(['id' => SORT_DESC])
                ->all();
        }
        return self::find()
            ->where(['=', 'vendor_id', $vendor_id])
            ->andWhere(['=', 'material_id', $material_id])
            ->andWhere(['=', 'object_id', $object_id])
            ->limit($limit)
            ->offset($offset)
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    public static function getAllObjectsByVendor($vendor_id): array
    {
        return self::find()
            ->where(['=', 'vendor_id', $vendor_id])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    public static function getAllMaterialByObject($vendor_id, $object_id = 0): array
    {
        return self::find()
            ->where(['=', 'vendor_id', $vendor_id])
            ->andWhere(['=', 'object_id', $object_id])
            ->all();
    }

    public static function getAllCount($vendor_id, $material_id = 0, $object_id = 0)
    {
        return self::find()
            ->where(['=', 'vendor_id', $vendor_id])
            ->andWhere(['=', 'material_id', $material_id])
            ->andWhere(['=', 'object_id', $object_id])
            ->count();
    }

    public static function create($data): bool
    {
        $model = new self();
        $model->vendor_id = $data['vendor_id'];
        $model->material_id = $data['material_id'];
        $model->object_id = $data['object_id'];
        $model->legal_entity_id = $data['legal_entity_id'];
        $model->price = $data['price'];
        $model->volume = $data['volume'];
        $model->total = $data['total'];
        $model->file_id = $data['file_id'];
        $model->comment = $data['comment'];
        $model->created_at = $data['created_at'];
        $model->is_debt = $data['is_debt'];
        $model->confirmed_data = $data['confirmed_data'];
        return $model->save();
    }

    public static function checkIsNotExitsId($id): bool
    {
        $type = self::find()->where(['=', 'id', $id])->one();
        if ($type) {
            return true;
        }
        return false;
    }

    public static function updateOperation($data): bool
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        $model->vendor_id = $data['vendor_id'];
        $model->legal_entity_id = $data['legal_entity_id'];
        $model->material_id = $data['material_id'];
        $model->object_id = $data['object_id'];
        $model->price = $data['price'];
        $model->volume = $data['volume'];
        $model->total = $data['total'];
        if ($data['file_id']) {
            $model->file_id = $data['file_id'];
        }
        $model->comment = $data['comment'];
        $model->created_at = $data['created_at'];
        $model->confirmed_data = $data['confirmed_data'];
        return $model->save();
    }

    public static function deleteOperation($id)
    {
        $model = self::find()->where(['=', 'id', $id])->one();
        return $model->delete();
    }

    public static function getOperationsByField($data): array
    {
        $query = self::find();
        foreach ($data as $key => $operation) {
            if ($key === 0) {
                $query = $query->where([$operation['operation'], $operation['field'], $operation['value']]);
            } else {
                $query = $query->andWhere([$operation['operation'], $operation['field'], $operation['value']]);
            }
        }
        return $query->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['vendor_id', 'material_id', 'created_at', 'updated_at', 'file_id', 'object_id', 'is_debt', 'confirmed_data', 'legal_entity_id'], 'integer'],
            [['volume', 'price', 'total'], 'number'],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'vendor_id' => 'Vendor ID',
            'material_id' => 'Material ID',
            'legal_entity_id' => 'Legal Entity ID',
            'object_id' => 'Object ID',
            'volume' => 'Volume',
            'price' => 'Price',
            'total' => 'Total',
            'file_id' => 'File ID',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_debt' => 'Is Debt',
            'confirmed_data' => 'Confirmed Data',
        ];
    }

    public function getVendor(): ActiveQuery
    {
        return $this->hasOne(Vendors::class, ['id' => 'vendor_id']);
    }

    public function getFile(): ActiveQuery
    {
        return $this->hasOne(Files::class, ['id' => 'file_id']);
    }

    public function getMaterial(): ActiveQuery
    {
        return $this->hasOne(Materials::class, ['id' => 'material_id']);
    }

    public function getObject(): ActiveQuery
    {
        return $this->hasOne(Objects::class, ['id' => 'object_id']);
    }

    public function getLegalEntity(): ActiveQuery
    {
        return $this->hasOne(LegalEntities::class, ['id' => 'legal_entity_id']);
    }

    public function getDebtOperations(): array
    {
        return self::find()->where(['=', 'is_debt', 1])->all();
    }
}