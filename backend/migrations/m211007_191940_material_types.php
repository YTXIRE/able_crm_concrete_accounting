<?php

use yii\db\Migration;

/**
 * Class m211007_191940_material_types
 */
class m211007_191940_material_types extends Migration
{
    public function up()
    {
        $this->createTable('material_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);
    }

    public function down()
    {
        $this->dropTable('material_types');
    }
}
