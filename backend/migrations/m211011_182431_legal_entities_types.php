<?php

use app\models\LegalEntitiesTypes;
use yii\db\Migration;

/**
 * Class m211011_182431_legal_entities_types
 */
class m211011_182431_legal_entities_types extends Migration
{
    public function up()
    {
        $this->createTable('legal_entities_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'full_name' => $this->string()->notNull(),
        ]);

        $legal_entities_types = [
            [
                'name' => 'ООО',
                'full_name' => 'Общество с ограниченной ответственностью',
            ],
            [
                'name' => 'ОАО',
                'full_name' => 'Открытое акционерное общество',
            ],
            [
                'name' => 'ЗАО',
                'full_name' => 'Закрытое акционерное общество',
            ],
            [
                'name' => 'ПАО',
                'full_name' => 'Публичное акционерное общество',
            ],
            [
                'name' => 'АО',
                'full_name' => 'Акционерное общество',
            ],
            [
                'name' => 'ИП',
                'full_name' => 'Индивидуальный предприниматель',
            ],
        ];

        foreach ($legal_entities_types as $legal_entities_type) {
            LegalEntitiesTypes::saveType($legal_entities_type);
        }
    }

    public function down()
    {
        $this->dropTable('legal_entities_types');
    }
}
