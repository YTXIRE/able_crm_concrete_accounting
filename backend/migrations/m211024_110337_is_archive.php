<?php

use app\models\Objects;
use app\models\Vendors;
use yii\db\Migration;

/**
 * Class m211024_110337_is_archive
 */
class m211024_110337_is_archive extends Migration
{
    /**
     * @throws Exception
     */
    public function up()
    {
        $this->addColumn('objects', 'is_archive', $this->integer()->defaultValue(0));

        foreach (Objects::find()->all() as $object) {
            $object['is_archive'] = 0;
            Objects::updateObject($object);
        }

        $this->addColumn('vendors', 'is_archive', $this->integer()->defaultValue(0));

        foreach (Vendors::find()->all() as $vendor) {
            $vendor['is_archive'] = 0;
            Vendors::updateVendor($vendor);
        }
    }

    public function down()
    {
        $this->dropColumn('objects', 'is_archive');
        $this->dropColumn('vendors', 'is_archive');
    }
}
