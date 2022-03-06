<?php

use app\models\MaterialTypes;
use app\models\Payments;
use yii\db\Migration;

/**
 * Class m220119_172911_payment
 */
class m220119_172911_payment extends Migration
{
    /**
     * @throws Exception
     */
    public function up()
    {
        $this->addColumn('payments', 'material_type_id', $this->integer()->defaultValue(0));
        foreach (Payments::find()->all() as $payment) {
            $payment['material_type_id'] = 1;
            Payments::updatePayment($payment);
        }
    }

    public function down()
    {
        $this->dropColumn('payments', 'material_type_id');
    }
}
