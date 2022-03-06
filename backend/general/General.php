<?php

namespace general;

use Constants;
use Yii;
use yii\helpers\Helpers;

class General
{
    public static function generalMethod($request, $code, $data, $self, $message)
    {
        Yii::$app->response->statusCode = $code;
        Helpers::log('error', $message, serialize($request), ['data' => $data]);
        return $self->asJson(
            Helpers::formResponse([
                'message' => $message
            ])
        );
    }

    public static function success($data, $request, $self)
    {
        Helpers::log('info', Constants::$SUCCESS_REQUEST, $request, ['data' => $data]);
        return $self->asJson(
            Helpers::formResponse([
                'data' => $data
            ])
        );
    }
}