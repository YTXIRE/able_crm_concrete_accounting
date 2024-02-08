<?php

use app\models\Users;
use yii\db\Migration;

/**
 * Class m240127_092524_update_users
 */
class m240127_092524_update_users extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'is_demo', $this->integer()->defaultValue(0));
        $this->addColumn('users', 'demo_activation_date', $this->integer()->defaultValue(null));
        $this->addColumn('users', 'is_deleted', $this->integer()->defaultValue(0));

        foreach (Users::find()->all() as $data) {
            $data['is_demo'] = 0;
            Users::updateUserInfo($data);
        }
    }

    public function down()
    {
        $this->dropColumn('users', 'is_archive');
        $this->dropColumn('users', 'demo_activation_date');
        $this->dropColumn('users', 'is_deleted');
    }
}
