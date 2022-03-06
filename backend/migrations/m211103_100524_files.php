<?php

use yii\db\Migration;

/**
 * Class m211103_100524_files
 */
class m211103_100524_files extends Migration
{
    public function up()
    {
        $this->addColumn('files', 'file_type', $this->string());
    }

    public function down()
    {
        $this->dropColumn('files', 'file_type');
    }
}
