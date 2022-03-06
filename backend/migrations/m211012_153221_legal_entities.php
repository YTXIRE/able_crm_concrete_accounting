<?php

use yii\db\Migration;

/**
 * Class m211012_153221_legal_entities
 */
class m211012_153221_legal_entities extends Migration
{
    public function up()
    {
        $this->createTable('legal_entities', [
            'id' => $this->primaryKey(),
            'legal_entities_type_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null)
        ]);
    }

    public function down()
    {
        $this->dropTable('legal_entities');
    }
}
