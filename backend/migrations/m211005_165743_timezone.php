<?php

use app\models\TimeZone;
use app\models\Users;
use app\models\UserTimeZone;
use yii\db\Migration;

/**
 * Class m211005_165743_timezone
 */
class m211005_165743_timezone extends Migration
{
    public function up()
    {
        $this->createTable('timezone', [
            'id' => $this->primaryKey(),
            'timezone_name' => $this->string()->notNull()
        ]);

        $timezones = [
            'Europe/Moscow', 'Africa/Accra', 'Europe/Gibraltar', 'America/Godthab', 'America/Danmarkshavn',
            'America/Scoresbysund', 'Africa/Bissau', 'America/Guyana', 'Asia/Hong_Kong', 'America/Tegucigalpa',
            'America/Port-au-Prince', 'America/Argentina/Buenos_Aires', 'America/Argentina/Cordoba',
            'America/Argentina/Salta', 'America/Argentina/Jujuy', 'America/Argentina/Tucuman',
            'America/Argentina/Catamarca', 'America/Argentina/La_Rioja', 'America/Argentina/San_Juan',
            'America/Argentina/Mendoza', 'America/Argentina/San_Luis', 'America/Argentina/Rio_Gallegos', 'America/Argentina/Ushuaia',
            'America/Bahia_Banderas', 'Asia/Kuala_Lumpur', 'Asia/Kuching', 'Africa/Maputo', 'Africa/Windhoek',
            'America/Campo_Grande', 'America/Cuiaba', 'America/Santarem', 'America/Porto_Velho', 'America/Boa_Vista',
            'America/Curacao', 'Indian/Christmas', 'Asia/Nicosia', 'Asia/Famagusta', 'Europe/Prague', 'Europe/Berlin',
            'America/Indiana/Knox', 'America/Menominee', 'America/North_Dakota/Center',
            'America/Indiana/Petersburg', 'America/Indiana/Vevay', 'America/Chicago', 'America/Indiana/Tell_City',
            'America/Indiana/Vincennes', 'America/Indiana/Winamac', 'America/Indiana/Marengo',
            'America/Inuvik', 'America/Creston', 'America/Dawson_Creek', 'America/Fort_Nelson', 'America/Vancouver',
            'America/Kentucky/Louisville', 'America/Kentucky/Monticello', 'America/Indiana/Indianapolis',
            'America/Lima', 'Pacific/Tahiti', 'Pacific/Marquesas', 'Pacific/Gambier', 'Pacific/Port_Moresby',
            'America/Manaus', 'America/Eirunepe', 'America/Rio_Branco', 'America/Nassau', 'Asia/Thimphu',
            'America/Mazatlan', 'America/Chihuahua', 'America/Ojinaga', 'America/Hermosillo', 'America/Tijuana',
            'America/Metlakatla', 'America/Yakutat', 'America/Nome', 'America/Adak', 'Pacific/Honolulu',
            'America/Mexico_City', 'America/Cancun', 'America/Merida', 'America/Monterrey', 'America/Matamoros',
            'America/Moncton', 'America/Goose_Bay', 'America/Blanc-Sablon', 'America/Toronto', 'America/Nipigon',
            'America/Montevideo', 'Asia/Samarkand', 'Asia/Tashkent', 'America/Caracas', 'Asia/Ho_Chi_Minh',
            'America/North_Dakota/New_Salem', 'America/North_Dakota/Beulah', 'America/Denver', 'America/Boise',
            'America/Phoenix', 'America/Los_Angeles', 'America/Anchorage', 'America/Juneau', 'America/Sitka',
            'America/Rainy_River', 'America/Resolute', 'America/Rankin_Inlet', 'America/Regina',
            'America/Recife', 'America/Araguaina', 'America/Maceio', 'America/Bahia', 'America/Sao_Paulo',
            'America/Swift_Current', 'America/Edmonton', 'America/Cambridge_Bay', 'America/Yellowknife',
            'America/Thule', 'Europe/Athens', 'Atlantic/South_Georgia', 'America/Guatemala', 'Pacific/Guam',
            'America/Thunder_Bay', 'America/Iqaluit', 'America/Pangnirtung', 'America/Atikokan', 'America/Winnipeg',
            'America/Whitehorse', 'America/Dawson', 'Indian/Cocos', 'Europe/Zurich', 'Africa/Abidjan',
            'Antarctica/Davis', 'Antarctica/DumontDUrville', 'Antarctica/Mawson', 'Antarctica/Palmer',
            'Antarctica/Rothera', 'Antarctica/Syowa', 'Antarctica/Troll', 'Antarctica/Vostok',
            'Asia/Almaty', 'Asia/Qyzylorda', 'Asia/Qostanay', 'Asia/Aqtobe', 'Asia/Aqtau', 'Asia/Atyrau',
            'Asia/Damascus', 'America/Grand_Turk', 'Africa/Ndjamena', 'Indian/Kerguelen', 'Asia/Bangkok',
            'Asia/Dushanbe', 'Pacific/Fakaofo', 'Asia/Dili', 'Asia/Ashgabat', 'Africa/Tunis', 'Pacific/Tongatapu',
            'Asia/Jerusalem', 'Asia/Kolkata', 'Indian/Chagos', 'Asia/Baghdad', 'Asia/Tehran', 'Atlantic/Reykjavik',
            'Asia/Kathmandu', 'Pacific/Nauru', 'Pacific/Niue', 'Pacific/Auckland', 'Pacific/Chatham', 'America/Panama',
            'Asia/Krasnoyarsk', 'Asia/Irkutsk', 'Asia/Chita', 'Asia/Yakutsk', 'Asia/Khandyga', 'Asia/Vladivostok',
            'Asia/Macau', 'America/Martinique', 'Europe/Malta', 'Indian/Mauritius', 'Indian/Maldives',
            'Asia/Oral', 'Asia/Beirut', 'Asia/Colombo', 'Africa/Monrovia', 'Europe/Vilnius', 'Europe/Luxembourg',
            'Asia/Riyadh', 'Pacific/Guadalcanal', 'Indian/Mahe', 'Africa/Khartoum', 'Europe/Stockholm',
            'Asia/Singapore', 'America/Paramaribo', 'Africa/Juba', 'Africa/Sao_Tome', 'America/El_Salvador',
            'Asia/Urumqi', 'America/Bogota', 'America/Costa_Rica', 'America/Havana', 'Atlantic/Cape_Verde',
            'Asia/Ust-Nera', 'Asia/Magadan', 'Asia/Sakhalin', 'Asia/Srednekolymsk', 'Asia/Kamchatka', 'Asia/Anadyr',
            'Asia/Yekaterinburg', 'Asia/Omsk', 'Asia/Novosibirsk', 'Asia/Barnaul', 'Asia/Tomsk', 'Asia/Novokuznetsk',
            'Atlantic/Azores', 'Pacific/Palau', 'America/Asuncion', 'Asia/Qatar', 'Indian/Reunion', 'Europe/Bucharest',
            'Atlantic/Bermuda', 'Asia/Brunei', 'America/La_Paz', 'America/Noronha', 'America/Belem', 'America/Fortaleza',
            'Australia/Brisbane', 'Australia/Lindeman', 'Australia/Adelaide', 'Australia/Darwin', 'Australia/Perth',
            'Australia/Currie', 'Australia/Melbourne', 'Australia/Sydney', 'Australia/Broken_Hill',
            'Australia/Eucla', 'Asia/Baku', 'America/Barbados', 'Asia/Dhaka', 'Europe/Brussels', 'Europe/Sofia',
            'Europe/Astrakhan', 'Europe/Volgograd', 'Europe/Saratov', 'Europe/Ulyanovsk', 'Europe/Samara',
            'Europe/Belgrade', 'Europe/Kaliningrad', 'Europe/Simferopol', 'Europe/Kirov',
            'Europe/Budapest', 'Asia/Jakarta', 'Asia/Pontianak', 'Asia/Makassar', 'Asia/Jayapura', 'Europe/Dublin',
            'Europe/Copenhagen', 'America/Santo_Domingo', 'Africa/Algiers', 'America/Guayaquil', 'Pacific/Galapagos',
            'Europe/Helsinki', 'Pacific/Fiji', 'Atlantic/Stanley', 'Pacific/Chuuk', 'Pacific/Pohnpei',
            'Europe/Istanbul', 'America/Port_of_Spain', 'Pacific/Funafuti', 'Asia/Taipei', 'Europe/Kiev',
            'Europe/Minsk', 'America/Belize', 'America/St_Johns', 'America/Halifax', 'America/Glace_Bay',
            'Europe/Riga', 'Africa/Tripoli', 'Africa/Casablanca', 'Europe/Monaco', 'Europe/Chisinau',
            'Europe/Rome', 'America/Jamaica', 'Asia/Amman', 'Asia/Tokyo', 'Africa/Nairobi', 'Asia/Bishkek',
            'Europe/Tallinn', 'Africa/Cairo', 'Africa/El_Aaiun', 'Europe/Madrid', 'Africa/Ceuta', 'Atlantic/Canary',
            'Europe/Uzhgorod', 'Europe/Zaporozhye', 'Pacific/Wake', 'America/New_York', 'America/Detroit',
            'Pacific/Bougainville', 'Asia/Manila', 'Asia/Karachi', 'Europe/Warsaw', 'America/Miquelon',
            'Pacific/Efate', 'Pacific/Wallis', 'Pacific/Apia', 'Africa/Johannesburg',
            'Pacific/Kosrae', 'Atlantic/Faroe', 'Europe/Paris', 'Europe/London', 'Asia/Tbilisi', 'America/Cayenne',
            'Pacific/Majuro', 'Pacific/Kwajalein', 'Asia/Yangon', 'Asia/Ulaanbaatar', 'Asia/Hovd', 'Asia/Choibalsan',
            'Pacific/Noumea', 'Pacific/Norfolk', 'Africa/Lagos', 'America/Managua', 'Europe/Amsterdam', 'Europe/Oslo',
            'Pacific/Pago_Pago', 'Europe/Vienna', 'Australia/Lord_Howe', 'Antarctica/Macquarie', 'Australia/Hobart',
            'Pacific/Pitcairn', 'America/Puerto_Rico', 'Asia/Gaza', 'Asia/Hebron', 'Europe/Lisbon', 'Atlantic/Madeira',
            'Pacific/Rarotonga', 'America/Santiago', 'America/Punta_Arenas', 'Pacific/Easter', 'Asia/Shanghai',
            'Pacific/Tarawa', 'Pacific/Enderbury', 'Pacific/Kiritimati', 'Asia/Pyongyang', 'Asia/Seoul',
            'Europe/Andorra', 'Asia/Dubai', 'Asia/Kabul', 'Europe/Tirane', 'Asia/Yerevan', 'Antarctica/Casey',
        ];

        foreach ($timezones as $timezone) {
            TimeZone::saveTimeZone($timezone);
        }

        $this->createTable('user_timezone', [
            'timezone_id' => $this->integer(),
            'user_id' => $this->integer()
        ]);

        $users = Users::find()->all();

        foreach ($users as $user) {
            UserTimeZone::saveTimeZone(1, $user->id);
        }
    }

    public function down()
    {
        $this->dropTable('timezone');
        $this->dropTable('user_timezone');
    }
}
