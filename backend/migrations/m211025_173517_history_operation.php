<?php

use yii\db\Migration;

/**
 * Class m211025_173517_history_operation
 */
class m211025_173517_history_operation extends Migration
{
    public function up()
    {
        $this->createTable('history_operation', [
            'id' => $this->primaryKey(),
            'vendor_id' => $this->integer(),
            'material_id' => $this->integer(),
            'object_id' => $this->integer(),
            'volume' => $this->decimal(13, 2),
            'price' => $this->decimal(13, 2),
            'total' => $this->decimal(13, 2),
            'file_id' => $this->integer(),
            'comment' => $this->text(),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ]);

        $this->addColumn('files', 'name', $this->string());
    }

    public function down()
    {
        $this->dropColumn('files', 'name');
        $this->dropTable('history_operation');
    }
}
