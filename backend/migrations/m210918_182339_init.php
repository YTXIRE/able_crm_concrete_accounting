<?php

use yii\db\Migration;

/**
 * Class m210918_182339_init
 */
class m210918_182339_init extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'login' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'created_at' => $this->integer()->defaultValue(null),
            'last_login_at' => $this->integer()->defaultValue(null),
        ]);
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
