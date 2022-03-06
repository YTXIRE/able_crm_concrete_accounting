<?php

use yii\db\Migration;


class m210919_090259_login extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'token', $this->string());
    }

    public function down()
    {
        $this->dropColumn('users', 'token');
    }
}
