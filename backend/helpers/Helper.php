<?php

namespace yii\helpers;

use Yii;

class Helpers
{
    public static function formResponse($params): array
    {
        $data = [
            'status' => Yii::$app->response->statusText,
            'code' => Yii::$app->response->statusCode,
        ];
        if (array_key_exists('message', $params)) {
            $data['message'] = $params['message'];
        }
        if (array_key_exists('data', $params)) {
            $data['data'] = $params['data'];
        }

        return $data;
    }

    public static function getMonth($month): ?string
    {
        switch ($month) {
            case '01':
                return 'Января';
            case '02':
                return 'Февраля';
            case '03':
                return 'Марта';
            case '04':
                return 'Апреля';
            case '05':
                return 'Мая';
            case '06':
                return 'Июня';
            case '07':
                return 'Июля';
            case '08':
                return 'Августа';
            case '09':
                return 'Сентября';
            case '10':
                return 'Октября';
            case '11':
                return 'Ноября';
            case '12':
                return 'Декабря';
        }
        return null;
    }

    public static function log($level, $message, $info, $data = '')
    {
        $log = [
            'Message' => $message,
            'Info' => $info,
            'Data' => $data
        ];
        if ($level == 'error') {
            Yii::error($log, 'CRM');
        }
    }
}