<?php

namespace app\models;

use DateTime;
use Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "files".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $name
 * @property string $file_type
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Files extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'files';
    }

    /**
     * @throws Exception
     */
    public static function saveFile($filename, $user_id, $file_type, $name = null): bool
    {
        $date = new DateTime();
        $model = new self();
        $model->user_id = $user_id;
        $model->filename = $filename;
        $model->name = $name;
        $model->file_type = $file_type;
        $model->created_at = $date->getTimestamp();
        return $model->save();
    }

    /**
     * @throws Exception
     */
    public static function updateFile($filename, $user_id, $file_type): bool
    {
        $date = new DateTime();
        $model = self::getUserFile($user_id, 'avatar');
        $model->filename = $filename;
        $model->file_type = $file_type;
        $model->updated_at = $date->getTimestamp();
        return $model->save();
    }

    public static function getUserFile($user_id, $file_type)
    {
        return self::find()->where(['=', 'user_id', $user_id])->andWhere(['=', 'file_type', $file_type])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'filename'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['filename', 'name', 'file_type'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'name' => 'Name',
            'file_type' => 'File Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
