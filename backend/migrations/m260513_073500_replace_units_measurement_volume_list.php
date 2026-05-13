<?php

use yii\db\Migration;
use yii\db\Query;

class m260513_073500_replace_units_measurement_volume_list extends Migration
{
    public function safeUp()
    {
        $this->addColumn('units_measurement_volume', 'short_name', $this->string()->notNull()->defaultValue(''));
        $this->addColumn('units_measurement_volume', 'full_name', $this->string()->notNull()->defaultValue(''));

        $legacyUnits = (new Query())
            ->select(['id', 'name'])
            ->from('units_measurement_volume')
            ->all();

        $newUnits = $this->getNewUnits();
        $rows = [];

        foreach ($newUnits as $unitName) {
            $rows[] = [$unitName['short_name'], $unitName['full_name']];
        }

        $this->batchInsert('units_measurement_volume', ['short_name', 'full_name'], $rows);

        $newUnitIdByShortName = [];
        $newUnitRows = (new Query())
            ->select(['id', 'short_name'])
            ->from('units_measurement_volume')
            ->where(['short_name' => array_column($newUnits, 'short_name')])
            ->all();

        foreach ($newUnitRows as $newUnitRow) {
            $newUnitIdByShortName[$newUnitRow['short_name']] = (int)$newUnitRow['id'];
        }

        $legacyUnitIds = array_map(static function ($legacyUnit) {
            return (int)$legacyUnit['id'];
        }, $legacyUnits);

        $legacyUnitIdsByName = [];

        foreach ($legacyUnits as $legacyUnit) {
            $legacyUnitIdsByName[$legacyUnit['name']][] = (int)$legacyUnit['id'];
        }

        foreach ($this->getLegacyToNewMap() as $legacyName => $newName) {
            if (!isset($legacyUnitIdsByName[$legacyName], $newUnitIdByShortName[$newName])) {
                continue;
            }

            $this->update(
                'material_types',
                ['units_measurement_volume_id' => $newUnitIdByShortName[$newName]],
                ['units_measurement_volume_id' => $legacyUnitIdsByName[$legacyName]]
            );
        }

        if (!empty($legacyUnitIds)) {
            $this->update(
                'material_types',
                ['units_measurement_volume_id' => $newUnitIdByShortName['ед']],
                ['units_measurement_volume_id' => $legacyUnitIds]
            );

            $this->delete('units_measurement_volume', ['id' => $legacyUnitIds]);
        }

        $this->dropColumn('units_measurement_volume', 'name');
    }

    public function safeDown()
    {
        echo "m260513_073500_replace_units_measurement_volume_list cannot be reverted.\n";
        return false;
    }

    private function getLegacyToNewMap(): array
    {
        return [
            'Метр' => 'м',
            'Километр' => 'км',
            'Дюйм' => 'дюйм',
            'Квадратный километр' => 'км²',
            'Ар' => 'ар',
            'Гектар' => 'га',
            'Квадратный метр' => 'м²',
            'Квадратный дециметр' => 'дм²',
            'Квадратный сантиметр' => 'см²',
            'Квадратный дюйм' => 'кв.дюйм',
            'Кубический метр' => 'м³',
            'Кубический дециметр' => 'дм³',
            'Гектолитр' => 'гл',
            'Тонна' => 'т',
            'Килограмм' => 'кг',
            'Грамм' => 'г',
            'Килловат-час' => 'кВт·ч',
            'Килловат' => 'кВт',
        ];
    }

    private function getNewUnits(): array
    {
        return [
            ['short_name' => 'шт', 'full_name' => 'Штука'],
            ['short_name' => 'ед', 'full_name' => 'Единица'],
            ['short_name' => 'усл.ед', 'full_name' => 'Условная единица'],
            ['short_name' => 'компл', 'full_name' => 'Комплект'],
            ['short_name' => 'набор', 'full_name' => 'Набор'],
            ['short_name' => 'пара', 'full_name' => 'Пара'],
            ['short_name' => 'парт', 'full_name' => 'Партия'],
            ['short_name' => 'усл.парт', 'full_name' => 'Условная партия'],
            ['short_name' => 'упак', 'full_name' => 'Упаковка'],
            ['short_name' => 'пач', 'full_name' => 'Пачка'],
            ['short_name' => 'кор', 'full_name' => 'Коробка'],
            ['short_name' => 'ящ', 'full_name' => 'Ящик'],
            ['short_name' => 'меш', 'full_name' => 'Мешок'],
            ['short_name' => 'пак', 'full_name' => 'Пакет'],
            ['short_name' => 'рул', 'full_name' => 'Рулон'],
            ['short_name' => 'бух', 'full_name' => 'Бухта'],
            ['short_name' => 'мот', 'full_name' => 'Моток'],
            ['short_name' => 'кат', 'full_name' => 'Катушка'],
            ['short_name' => 'касс', 'full_name' => 'Кассета'],
            ['short_name' => 'боб', 'full_name' => 'Бобина'],
            ['short_name' => 'лист', 'full_name' => 'Лист'],
            ['short_name' => 'пл', 'full_name' => 'Плита'],
            ['short_name' => 'пан', 'full_name' => 'Панель'],
            ['short_name' => 'блок', 'full_name' => 'Блок'],
            ['short_name' => 'кирп', 'full_name' => 'Кирпич'],
            ['short_name' => 'секц', 'full_name' => 'Секция'],
            ['short_name' => 'звено', 'full_name' => 'Звено'],
            ['short_name' => 'элем', 'full_name' => 'Элемент'],
            ['short_name' => 'изд', 'full_name' => 'Изделие'],
            ['short_name' => 'мод', 'full_name' => 'Модуль'],
            ['short_name' => 'узел', 'full_name' => 'Узел'],
            ['short_name' => 'точка', 'full_name' => 'Точка'],
            ['short_name' => 'контур', 'full_name' => 'Контур'],
            ['short_name' => 'линия', 'full_name' => 'Линия'],
            ['short_name' => 'проём', 'full_name' => 'Проём'],
            ['short_name' => 'карта', 'full_name' => 'Карта'],
            ['short_name' => 'полоса', 'full_name' => 'Полоса'],
            ['short_name' => 'лента', 'full_name' => 'Лента'],
            ['short_name' => 'проф', 'full_name' => 'Профиль'],
            ['short_name' => 'стойка', 'full_name' => 'Стойка'],
            ['short_name' => 'балка', 'full_name' => 'Балка'],
            ['short_name' => 'ферма', 'full_name' => 'Ферма'],
            ['short_name' => 'рама', 'full_name' => 'Рама'],
            ['short_name' => 'полотно', 'full_name' => 'Полотно'],
            ['short_name' => 'створка', 'full_name' => 'Створка'],
            ['short_name' => 'марш', 'full_name' => 'Марш'],
            ['short_name' => 'ступ', 'full_name' => 'Ступень'],
            ['short_name' => 'пролёт', 'full_name' => 'Пролёт'],
            ['short_name' => 'захв', 'full_name' => 'Захватка'],
            ['short_name' => 'подд', 'full_name' => 'Поддон'],
            ['short_name' => 'пал', 'full_name' => 'Паллета'],
            ['short_name' => 'европал', 'full_name' => 'Европаллета'],
            ['short_name' => 'конт', 'full_name' => 'Контейнер'],
            ['short_name' => 'евро', 'full_name' => 'Еврокуб'],
            ['short_name' => 'бочка', 'full_name' => 'Бочка'],
            ['short_name' => 'цист', 'full_name' => 'Цистерна'],
            ['short_name' => 'тюб', 'full_name' => 'Тюбик'],
            ['short_name' => 'карт', 'full_name' => 'Картридж'],
            ['short_name' => 'бал', 'full_name' => 'Баллон'],
            ['short_name' => 'вед', 'full_name' => 'Ведро'],
            ['short_name' => 'банк', 'full_name' => 'Банка'],
            ['short_name' => 'кан', 'full_name' => 'Канистра'],
            ['short_name' => 'флак', 'full_name' => 'Флакон'],
            ['short_name' => 'бут', 'full_name' => 'Бутылка'],
            ['short_name' => 'амп', 'full_name' => 'Ампула'],
            ['short_name' => 'шприц', 'full_name' => 'Шприц'],
            ['short_name' => 'мм', 'full_name' => 'Миллиметр'],
            ['short_name' => 'см', 'full_name' => 'Сантиметр'],
            ['short_name' => 'дм', 'full_name' => 'Дециметр'],
            ['short_name' => 'м', 'full_name' => 'Метр'],
            ['short_name' => 'км', 'full_name' => 'Километр'],
            ['short_name' => 'дюйм', 'full_name' => 'Дюйм'],
            ['short_name' => 'фут', 'full_name' => 'Фут'],
            ['short_name' => 'ярд', 'full_name' => 'Ярд'],
            ['short_name' => 'п.м', 'full_name' => 'Погонный метр'],
            ['short_name' => 'м.пог', 'full_name' => 'Метр погонный'],
            ['short_name' => 'м.лин', 'full_name' => 'Метр линейный'],
            ['short_name' => 'мм²', 'full_name' => 'Квадратный миллиметр'],
            ['short_name' => 'см²', 'full_name' => 'Квадратный сантиметр'],
            ['short_name' => 'дм²', 'full_name' => 'Квадратный дециметр'],
            ['short_name' => 'м²', 'full_name' => 'Квадратный метр'],
            ['short_name' => 'км²', 'full_name' => 'Квадратный километр'],
            ['short_name' => 'га', 'full_name' => 'Гектар'],
            ['short_name' => 'ар', 'full_name' => 'Ар'],
            ['short_name' => 'сот', 'full_name' => 'Сотка'],
            ['short_name' => 'кв.дюйм', 'full_name' => 'Квадратный дюйм'],
            ['short_name' => 'кв.фут', 'full_name' => 'Квадратный фут'],
            ['short_name' => 'кв.ярд', 'full_name' => 'Квадратный ярд'],
            ['short_name' => 'мм³', 'full_name' => 'Кубический миллиметр'],
            ['short_name' => 'см³', 'full_name' => 'Кубический сантиметр'],
            ['short_name' => 'дм³', 'full_name' => 'Кубический дециметр'],
            ['short_name' => 'м³', 'full_name' => 'Кубический метр'],
            ['short_name' => 'км³', 'full_name' => 'Кубический километр'],
            ['short_name' => 'куб.дюйм', 'full_name' => 'Кубический дюйм'],
            ['short_name' => 'куб.фут', 'full_name' => 'Кубический фут'],
            ['short_name' => 'мл', 'full_name' => 'Миллилитр'],
            ['short_name' => 'л', 'full_name' => 'Литр'],
            ['short_name' => 'дл', 'full_name' => 'Децилитр'],
            ['short_name' => 'дал', 'full_name' => 'Декалитр'],
            ['short_name' => 'гл', 'full_name' => 'Гектолитр'],
            ['short_name' => 'кл', 'full_name' => 'Килолитр'],
            ['short_name' => 'мг', 'full_name' => 'Миллиграмм'],
            ['short_name' => 'г', 'full_name' => 'Грамм'],
            ['short_name' => 'кг', 'full_name' => 'Килограмм'],
            ['short_name' => 'ц', 'full_name' => 'Центнер'],
            ['short_name' => 'т', 'full_name' => 'Тонна'],
            ['short_name' => 'кт', 'full_name' => 'Килотонна'],
            ['short_name' => 'фунт', 'full_name' => 'Фунт'],
            ['short_name' => 'унц', 'full_name' => 'Унция'],
            ['short_name' => 'кг/м', 'full_name' => 'Килограмм на метр'],
            ['short_name' => 'кг/п.м', 'full_name' => 'Килограмм на погонный метр'],
            ['short_name' => 'кг/м²', 'full_name' => 'Килограмм на квадратный метр'],
            ['short_name' => 'кг/м³', 'full_name' => 'Килограмм на кубический метр'],
            ['short_name' => 'г/см³', 'full_name' => 'Грамм на кубический сантиметр'],
            ['short_name' => 'т/м³', 'full_name' => 'Тонна на кубический метр'],
            ['short_name' => 'л/м²', 'full_name' => 'Литр на квадратный метр'],
            ['short_name' => 'кг/м²/слой', 'full_name' => 'Килограмм на квадратный метр за слой'],
            ['short_name' => 'л/м²/слой', 'full_name' => 'Литр на квадратный метр за слой'],
            ['short_name' => 'м²/л', 'full_name' => 'Квадратный метр на литр'],
            ['short_name' => 'м²/кг', 'full_name' => 'Квадратный метр на килограмм'],
            ['short_name' => 'м²/упак', 'full_name' => 'Квадратный метр в упаковке'],
            ['short_name' => 'шт/упак', 'full_name' => 'Штук в упаковке'],
            ['short_name' => 'шт/м²', 'full_name' => 'Штук на квадратный метр'],
            ['short_name' => 'шт/м.пог', 'full_name' => 'Штук на погонный метр'],
            ['short_name' => 'кг/упак', 'full_name' => 'Килограмм в упаковке'],
            ['short_name' => 'л/упак', 'full_name' => 'Литр в упаковке'],
            ['short_name' => 'Вт', 'full_name' => 'Ватт'],
            ['short_name' => 'кВт', 'full_name' => 'Киловатт'],
            ['short_name' => 'МВт', 'full_name' => 'Мегаватт'],
            ['short_name' => 'кВт·ч', 'full_name' => 'Киловатт-час'],
            ['short_name' => 'МВт·ч', 'full_name' => 'Мегаватт-час'],
            ['short_name' => 'Вт/м²', 'full_name' => 'Ватт на квадратный метр'],
            ['short_name' => 'В', 'full_name' => 'Вольт'],
            ['short_name' => 'кВ', 'full_name' => 'Киловольт'],
            ['short_name' => 'А', 'full_name' => 'Ампер'],
            ['short_name' => 'мА', 'full_name' => 'Миллиампер'],
            ['short_name' => 'Ом', 'full_name' => 'Ом'],
            ['short_name' => 'кОм', 'full_name' => 'Килоом'],
            ['short_name' => 'Гц', 'full_name' => 'Герц'],
            ['short_name' => 'кГц', 'full_name' => 'Килогерц'],
            ['short_name' => 'Н', 'full_name' => 'Ньютон'],
            ['short_name' => 'кН', 'full_name' => 'Килоньютон'],
            ['short_name' => 'Па', 'full_name' => 'Паскаль'],
            ['short_name' => 'кПа', 'full_name' => 'Килопаскаль'],
            ['short_name' => 'МПа', 'full_name' => 'Мегапаскаль'],
            ['short_name' => 'бар', 'full_name' => 'Бар'],
            ['short_name' => 'атм', 'full_name' => 'Атмосфера'],
            ['short_name' => 'кгс/см²', 'full_name' => 'Килограмм-сила на квадратный сантиметр'],
            ['short_name' => 'Н/м²', 'full_name' => 'Ньютон на квадратный метр'],
            ['short_name' => 'Н/мм²', 'full_name' => 'Ньютон на квадратный миллиметр'],
            ['short_name' => 'кН/м²', 'full_name' => 'Килоньютон на квадратный метр'],
            ['short_name' => 'кН/м³', 'full_name' => 'Килоньютон на кубический метр'],
            ['short_name' => '°C', 'full_name' => 'Градус Цельсия'],
            ['short_name' => 'К', 'full_name' => 'Кельвин'],
            ['short_name' => '%', 'full_name' => 'Процент'],
            ['short_name' => 'пром', 'full_name' => 'Промилле'],
            ['short_name' => 'об.%', 'full_name' => 'Объёмный процент'],
            ['short_name' => 'мас.%', 'full_name' => 'Массовый процент'],
            ['short_name' => 'с', 'full_name' => 'Секунда'],
            ['short_name' => 'мин', 'full_name' => 'Минута'],
            ['short_name' => 'ч', 'full_name' => 'Час'],
            ['short_name' => 'сут', 'full_name' => 'Сутки'],
            ['short_name' => 'нед', 'full_name' => 'Неделя'],
            ['short_name' => 'мес', 'full_name' => 'Месяц'],
            ['short_name' => 'год', 'full_name' => 'Год'],
            ['short_name' => 'м/с', 'full_name' => 'Метр в секунду'],
            ['short_name' => 'м/мин', 'full_name' => 'Метр в минуту'],
            ['short_name' => 'км/ч', 'full_name' => 'Километр в час'],
            ['short_name' => 'л/с', 'full_name' => 'Литр в секунду'],
            ['short_name' => 'л/мин', 'full_name' => 'Литр в минуту'],
            ['short_name' => 'м³/с', 'full_name' => 'Кубический метр в секунду'],
            ['short_name' => 'м³/мин', 'full_name' => 'Кубический метр в минуту'],
            ['short_name' => 'м³/ч', 'full_name' => 'Кубический метр в час'],
            ['short_name' => 'кг/ч', 'full_name' => 'Килограмм в час'],
            ['short_name' => 'т/ч', 'full_name' => 'Тонна в час'],
            ['short_name' => 'слой', 'full_name' => 'Слой'],
            ['short_name' => 'проход', 'full_name' => 'Проход'],
            ['short_name' => 'цикл', 'full_name' => 'Цикл'],
            ['short_name' => 'операц', 'full_name' => 'Операция'],
            ['short_name' => 'смена', 'full_name' => 'Смена'],
            ['short_name' => 'рейс', 'full_name' => 'Рейс'],
            ['short_name' => 'заезд', 'full_name' => 'Заезд'],
            ['short_name' => 'выезд', 'full_name' => 'Выезд'],
            ['short_name' => 'доставка', 'full_name' => 'Доставка'],
            ['short_name' => 'монтаж', 'full_name' => 'Монтаж'],
            ['short_name' => 'демонтаж', 'full_name' => 'Демонтаж'],
        ];
    }
}
