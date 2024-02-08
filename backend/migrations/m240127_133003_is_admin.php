<?php

use yii\db\Migration;

/**
 * Class m240127_133003_is_admin
 */
class m240127_133003_is_admin extends Migration
{
    public function up()
    {
        $this->addColumn('rights', 'is_report', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('rights', 'is_report');
    }
}
