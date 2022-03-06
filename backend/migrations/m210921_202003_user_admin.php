<?php

use app\models\Users;
use yii\db\Migration;

/**
 * Class m210921_202003_user_admin
 */
class m210921_202003_user_admin extends Migration
{
    /**
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $date = new DateTime();
        $this->insert('users', [
            'login' => 'admin',
            'email' => 'admin@it-paradise.ru',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
            'created_at' => $date->getTimestamp(),
            'last_login_at' => null,
        ]);
    }

    public function down()
    {
        $id = Users::getUserIdWithLogin('admin')['id'];
        $this->delete('users', ['id' => $id]);
    }
}
