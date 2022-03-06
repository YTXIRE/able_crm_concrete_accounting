<?php

use yii\db\Migration;

/**
 * Class m211008_200051_materials
 */
class m211008_200051_materials extends Migration
{
    public function up()
    {
        $this->createTable('materials', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull(),
            'name' => $this->string(),
        ]);
    }

    public function down()
    {
        $this->dropTable('materials');
    }
}
