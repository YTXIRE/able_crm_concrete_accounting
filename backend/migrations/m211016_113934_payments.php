<?php

use yii\db\Migration;

/**
 * Class m211016_113934_payments
 */
class m211016_113934_payments extends Migration
{
    public function up()
    {
        $this->createTable('payments', [
            'id' => $this->primaryKey(),
            'vendor_id' => $this->integer(),
            'legal_entity_id' => $this->integer(),
            'amount' => $this->decimal(13, 2),
            'created_at' => $this->integer()->defaultValue(null),
            'updated_at' => $this->integer()->defaultValue(null),
        ]);
    }

    public function down()
    {
        $this->dropTable('payments');
    }
}
