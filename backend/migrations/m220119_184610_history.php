<?php

use app\models\HistoryOperation;
use yii\db\Migration;

/**
 * Class m220119_184610_history
 */
class m220119_184610_history extends Migration
{
    public function up()
    {
        $this->addColumn('history_operation', 'legal_entity_id', $this->integer()->defaultValue(0));
        foreach (HistoryOperation::find()->all() as $operation) {
            $operation['legal_entity_id'] = 1;
            HistoryOperation::updateOperation($operation);
        }
    }

    public function down()
    {
        $this->dropColumn('history_operation', 'legal_entity_id');
    }
}
