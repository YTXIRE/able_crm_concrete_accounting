<?php

namespace app\controllers\api;

use app\models\UnitsMeasurementVolume;
use app\models\Users;
use Constants;
use general\General;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\web\Response;

class UnitsMeasurementVolumeController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Get(
     *     path="/api/units-measurement-volume/get-all",
     *     summary="Получение всех величин объема",
     *     operationId="get-all",
     *     tags={"units-measurement-volume"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Токен пользователя",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="OK",
     *                 summary="",
     *                 value={
     *                     "code": 200,
     *                     "status": "OK",
     *                     "data": {
     *                          "legal_entities": {
     *                              {
     *                                  "id": 1,
     *                                  "name": "Метр",
     *                              }
     *                          },
     *                          "count": 1
     *                      }
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверные данные",
     *         @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Examples(
     *                 example="Пожалуйста, укажите токен пользователя",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, укажите токен пользователя"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина токена может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина токена может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Идентификатор должен быть целым числом и должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Идентификатор должен быть целым числом и должен быть больше нуля"
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Данные не найдены",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="Пользователь с указанным токеном не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Пользователь с указанным токеном не найден"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Пользователь с указанным токеном и идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Пользователь с указанным токеном и идентификатором не найден"
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Метод не разрешен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="Пожалуйста, используйте метод GET для этого запроса",
     *                 summary="",
     *                 value={
     *                     "code": 405,
     *                     "status": "Method Not Allowed",
     *                     "message": "Пожалуйста, используйте метод GET для этого запроса"
     *                  }
     *              )
     *          )
     *     )
     * )
     */
    function actionGetAll($token = '', $user_id = 0): Response
    {
        try {
            $request = Yii::$app->request;
            if (!$request->isGet) {
                return General::generalMethod($request, 405, [], $this, Constants::$GET_METHOD_NOT_ALLOWED);
            }
            $token = trim($token);
            $user_id = (int)$user_id;
            if (empty($token)) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!is_int($user_id) || $user_id <= 0) {
                return General::generalMethod($request, 400, [$user_id], $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            return General::success(UnitsMeasurementVolume::getAll() ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}