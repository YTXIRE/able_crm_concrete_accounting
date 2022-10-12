<?php

use app\models\Payments;
use yii\db\Migration;

/**
 * Class m221012_184610_operations
 */
class m220118_184610_operations extends Migration
{
    /**
     * @throws Exception
     */
    public function up()
    {
        $this->addColumn('payments', 'operation_type', $this->string()->notNull());
        foreach (Payments::find()->all() as $payment) {
            $payment['operation_type'] = 'buy';
            Payments::updatePayment($payment);
        }
    }

    public function down()
    {
        $this->dropColumn('payments', 'operation_type');
    }
}
