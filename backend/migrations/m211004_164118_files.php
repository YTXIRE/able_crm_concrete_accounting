<?php

use yii\db\Migration;

/**
 * Class m211004_164118_files
 */
class m211004_164118_files extends Migration
{
    public function up()
    {
        $this->createTable('files', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'filename' => $this->string()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null)
        ]);
    }

    public function down()
    {
        $this->dropTable('files');
    }
}
