<?php

use yii\db\Migration;

/**
 * Class m211124_191025_reports
 */
class m211124_191025_reports extends Migration
{
    /**
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $this->createTable('filters', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'filter' => $this->json()
        ]);
    }

    public function down()
    {
        $this->dropTable('filters');
    }
}
