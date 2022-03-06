<?php

use app\models\LegalEntities;
use yii\db\Migration;

/**
 * Class m211023_191115_remove_methods
 */
class m211023_191115_remove_methods extends Migration
{
    /**
     * @throws Exception
     */
    public function up()
    {
        $this->addColumn('legal_entities', 'is_archive', $this->integer()->defaultValue(0));

        foreach (LegalEntities::find()->all() as $legal_entity) {
            $legal_entity['is_archive'] = 0;
            LegalEntities::updateLegalEntities($legal_entity);
        }
    }

    public function down()
    {
        $this->dropColumn('legal_entities', 'is_archive');
    }
}
