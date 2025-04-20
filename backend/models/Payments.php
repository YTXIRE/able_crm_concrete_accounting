<?php

namespace app\models;

use DateTime;
use Exception;
use Throwable;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int|null $vendor_id
 * @property int|null $legal_entity_id
 * @property int|null $material_type_id
 * @property float|null $summa
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Payments extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'payments';
    }

    public static function savePayment($data): bool
    {
        $model = new self();
        $model->vendor_id = $data['vendor_id'];
        $model->legal_entity_id = $data['legal_entity_id'];
        $model->amount = $data['amount'];
        $model->created_at = $data['created_at'];
        $model->operation_type = $data['operation_type'];
        $model->material_type_id = $data['material_type_id'];
        return $model->save();
    }

    public static function getAll($limit = 0, $offset = 0, $legal_entity_id = 0): array
    {
        if ($limit === 0 && $offset === 0 && $legal_entity_id === 0) {
            return self::find()->orderBy(['id' => SORT_DESC])->all();
        }
        if ($limit === 0 && $offset === 0) {
            return self::find()->orderBy(['id' => SORT_DESC])->all();
        }
        return self::find()->where(['=', 'legal_entity_id', $legal_entity_id])->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->all();
    }

    public static function getAllPaymentsByVendors($limit = 0, $offset = 0, $vendor_id = 0): array
    {
        if ($limit === 0 && $offset === 0) {
            return self::find()->where(['=', 'vendor_id', $vendor_id])->orderBy(['id' => SORT_DESC])->all();
        }
        return self::find()->where(['=', 'vendor_id', $vendor_id])->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->all();
    }

    public static function getAllCount($legal_entity_id)
    {
        return self::find()->where(['=', 'legal_entity_id', $legal_entity_id])->count();
    }

    /**
     * @throws Exception
     */
    public static function updatePayment($data): bool
    {
        $model = self::find()->where(['=', 'id', $data['id']])->one();
        if ($model) {
            $date = new DateTime();
            $model->vendor_id = $data['vendor_id'];
            $model->legal_entity_id = $data['legal_entity_id'];
            $model->amount = $data['amount'];
            $model->material_type_id = $data['material_type_id'];
            $model->created_at = $data['created_at'];
            $model->updated_at = $date->getTimestamp();
            $model->operation_type = $data['operation_type'];
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
     * @throws StaleObjectException
     * @throws Throwable
     */
    public static function deletePayment($id)
    {
        $model = self::find()->where(['=', 'id', $id])->one();
        return $model->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['vendor_id', 'legal_entity_id', 'created_at', 'updated_at', 'material_type_id'], 'integer'],
            [['amount'], 'number'],
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
            'legal_entity_id' => 'Legal Entity ID',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'material_type_id' => 'Material Type Id',
        ];
    }

    public function getVendor(): ActiveQuery
    {
        return $this->hasOne(Vendors::class, ['id' => 'vendor_id']);
    }

    public function getLegalEntity(): ActiveQuery
    {
        return $this->hasOne(LegalEntities::class, ['id' => 'legal_entity_id']);
    }

    public function getMaterialType(): ActiveQuery
    {
        return $this->hasOne(MaterialTypes::class, ['id' => 'material_type_id']);
    }
}
