<?php

use yii\db\Migration;

/**
 * Class m211105_161922_docs
 */
class m211105_161922_docs extends Migration
{
    public function up()
    {
        $this->addColumn('history_operation', 'confirmed_data', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('history_operation', 'confirmed_data');
    }
}
