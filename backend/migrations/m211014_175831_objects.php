<?php

use yii\db\Migration;

/**
 * Class m211014_175831_objects
 */
class m211014_175831_objects extends Migration
{
    public function up()
    {
        $this->createTable('objects', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('objects');
    }
}
