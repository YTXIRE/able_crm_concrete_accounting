<?php

use app\models\Users;
use yii\db\Migration;

/**
 * Class m210921_202934_is_admin
 */
class m210921_202934_is_admin extends Migration
{
    public function up()
    {
        $this->createTable('rights', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'is_admin' => $this->integer(1),
        ]);
        $this->insert('rights', [
            'user_id' => Users::getUserIdWithLogin('admin')['id'],
            'is_admin' => 1
        ]);
    }

    public function down()
    {
        $this->dropTable('rights');
    }
}
