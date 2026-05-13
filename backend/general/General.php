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
        Helpers::log('error', $message, self::normalizeRequestInfo($request), ['data' => $data]);
        return $self->asJson(
            Helpers::formResponse([
                'message' => $message
            ])
        );
    }

    public static function success($data, $request, $self)
    {
        Helpers::log('info', Constants::$SUCCESS_REQUEST, self::normalizeRequestInfo($request), ['data' => $data]);
        return $self->asJson(
            Helpers::formResponse([
                'data' => $data
            ])
        );
    }

    private static function normalizeRequestInfo($request): string
    {
        if (is_object($request)) {
            try {
                return json_encode([
                    'class' => get_class($request),
                    'method' => $request->method ?? null,
                    'url' => $request->absoluteUrl ?? ($request->url ?? null),
                ], JSON_UNESCAPED_UNICODE);
            } catch (\Throwable $e) {
                return get_class($request);
            }
        }

        if (is_array($request)) {
            return json_encode($request, JSON_UNESCAPED_UNICODE);
        }

        return (string)$request;
    }
}
