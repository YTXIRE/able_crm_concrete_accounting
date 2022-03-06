<?php

use app\models\Settings;
use yii\db\Migration;

/**
 * Class m211105_123433_settings
 */
class m211105_123433_settings extends Migration
{
    public function up()
    {
        $this->addColumn('history_operation', 'is_debt', $this->integer());

        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'active' => $this->integer()
        ]);

        Settings::createSettings(['name' => 'debt', 'active' => 0]);
    }


    public function down()
    {
        $this->dropColumn('history_operation', 'is_debt');

        $this->dropTable('settings');
    }
}
