<?php

use app\models\MaterialTypes;
use app\models\UnitsMeasurementVolume;
use yii\db\Migration;

/**
 * Class m211029_175309_units_measurement_volume
 */
class m211029_175309_units_measurement_volume extends Migration
{
    public function up()
    {
        $this->createTable('units_measurement_volume', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);

        $data = [
            'Метр',
            'Километр',
            'Дюйм',
            'Квадратный километр',
            'Ар',
            'Гектар',
            'Квадратный метр',
            'Квадратный дециметр',
            'Квадратный сантиметр',
            'Квадратный дюйм',
            'Кубический метр',
            'Кубический дециметр',
            'Гектолитр',
            'Тонна',
            'Килограмм',
            'Грамм',
            'Килловат-час',
            'Килловат'
        ];

        foreach ($data as $datum) {
            UnitsMeasurementVolume::saveUnits($datum);
        }

        $this->addColumn('material_types', 'units_measurement_volume_id', $this->integer());

        foreach (MaterialTypes::getAll(0, 0) as $item) {
            $item['units_measurement_volume_id'] = 1;
            MaterialTypes::updateType($item);
        }
    }

    public function down()
    {
        $this->dropColumn('material_types', 'units_measurement_volume_id');
        $this->dropTable('units_measurement_volume');
    }
}
