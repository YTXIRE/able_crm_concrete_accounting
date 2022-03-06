<?php

namespace app\controllers\api;

use app\models\MaterialTypes;
use app\models\UnitsMeasurementVolume;
use app\models\Users;
use Constants;
use general\General;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\web\Response;

class MaterialTypesController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Get(
     *     path="/api/material-types/get-all",
     *     summary="Получение всех типов материалов",
     *     operationId="get-all",
     *     tags={"material-types"},
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
     *         name="limit",
     *         in="query",
     *         description="Лимит",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Смещение",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
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
     *                          "types": {
     *                              {
     *                                  "id": 1,
     *                                  "name": "Тип материала 1",
     *                                  "units_measurement_volume": {
     *                                      "id": 14,
     *                                      "name": "Тонна"
     *                                  }
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
     *              ),
     *              @OA\Examples(
     *                 example="Лимит должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Лимит должен быть больше нуля"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Смещение должно быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Смещение должно быть больше нуля"
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
    function actionGetAll($token = '', $user_id = 0, $limit = 0, $offset = 0): Response
    {
        try {
            $request = Yii::$app->request;
            if (!$request->isGet) {
                return General::generalMethod($request, 405, [], $this, Constants::$GET_METHOD_NOT_ALLOWED);
            }
            $token = trim($token);
            $user_id = (int)$user_id;
            $limit = (int)$limit;
            $offset = (int)$offset;
            if (empty($token)) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!is_int($user_id) || $user_id <= 0) {
                return General::generalMethod($request, 400, [$user_id], $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if ($limit < 0) {
                return General::generalMethod($request, 400, [$limit], $this, Constants::$LIMIT_MUST_BE_GREATER_THAN_ZERO);
            }
            if ($offset < 0) {
                return General::generalMethod($request, 400, [$offset], $this, Constants::$OFFSET_MUST_BE_GREATER_THAN_ZERO);
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $material_types = [];
            foreach (MaterialTypes::getAll($limit, $offset) as $item) {
                $material_types[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'units_measurement_volume' => [
                        'id' => $item->units['id'],
                        'name' => $item->units['name'],
                    ],
                ];
            }
            return General::success([
                'types' => $material_types,
                'count' => (int)MaterialTypes::getAllCount()
            ], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/material-types/create",
     *     summary="Создание нового типа материала",
     *     operationId="create",
     *     tags={"material-types"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     description="Токен пользователя",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="Название типа",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="ID пользователя",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="units_measurement_volume_id",
     *                     description="ID величины объема",
     *                     type="integer"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="OK",
     *                 summary="",
     *                 value={
     *                     "code": 201,
     *                     "status": "OK",
     *                     "message": "Новый тип материала успешно создан"
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
     *                 example="Пожалуйста, укажите название типа материала",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, укажите название типа материала"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина названия типа материала может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина названия типа материала может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Тип материала с указанным названием уже существует",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Тип материала с указанным названием уже существует"
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
     *                 example="Величина объема с указанным идентификатором не найдена",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Величина объема с указанным идентификатором не найдена"
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
     *                 example="Пожалуйста, используйте метод POST для этого запроса",
     *                 summary="",
     *                 value={
     *                     "code": 405,
     *                     "status": "Method Not Allowed",
     *                     "message": "Пожалуйста, используйте метод POST для этого запроса"
     *                  }
     *              )
     *          )
     *     )
     * )
     */
    function actionCreate(): Response
    {
        try {
            $request = Yii::$app->request;
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }
            if (!$request->isPost) {
                return General::generalMethod($request, 405, [], $this, Constants::$POST_METHOD_NOT_ALLOWED);
            }
            $data = $request->bodyParams;
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'name' => array_key_exists('name', $data) ? trim($data['name']) : '',
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
                'units_measurement_volume_id' => array_key_exists('units_measurement_volume_id', $data) ? (int)($data['units_measurement_volume_id']) : 0,
            ];
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (empty($data['name'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_NAME_OF_THE_TYPE_OF_MATERIAL);
            }
            if (mb_strlen($data['name']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_OF_THE_NAME_OF_THE_TYPE_OF_MATERIAL_CAN_BE_100_CHARACTERS);
            }
            if (!is_int($data['user_id']) || $data['user_id'] <= 0 || !is_int($data['units_measurement_volume_id']) || $data['units_measurement_volume_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (MaterialTypes::checkIsNotExitsName($data['name'])) {
                return General::generalMethod($request, 400, [], $this, Constants::$TYPE_MATERIAL_WITH_NAME_ALREADY_EXISTS);
            }
            if (!UnitsMeasurementVolume::checkUnit($data['units_measurement_volume_id'])) {
                return General::generalMethod($request, 404, [], $this, Constants::$UNITS_VOLUME_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $result = MaterialTypes::saveType($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 201, [], $this, Constants::$NEW_TYPE_MATERIAL_SUCCESSFULLY_CREATED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/material-types/update",
     *     summary="Обновление названия типа материала",
     *     operationId="update",
     *     tags={"material-types"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID типа материала",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     description="Токен пользователя",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="Название типа материала",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="ID пользователя",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="units_measurement_volume_id",
     *                     description="ID величины объема",
     *                     type="integer"
     *                 ),
     *             )
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
     *                          "id": 1,
     *                          "name": "Тип материала 1"
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
     *                 example="Пожалуйста, укажите название типа материала",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, укажите название типа материала"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина названия типа материала может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина названия типа материала может быть 100 символов"
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
     *                 example="Тип материала с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Тип материала с указанным идентификатором не найден"
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
     *              ),
     *              @OA\Examples(
     *                 example="Величина объема с указанным идентификатором не найдена",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Величина объема с указанным идентификатором не найдена"
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
     *                 example="Пожалуйста, используйте метод PUT для этого запроса",
     *                 summary="",
     *                 value={
     *                     "code": 405,
     *                     "status": "Method Not Allowed",
     *                     "message": "Пожалуйста, используйте метод PUT для этого запроса"
     *                  }
     *              )
     *          )
     *     ),
     * )
     */
    function actionUpdate(): Response
    {
        try {
            $request = Yii::$app->request;
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }
            if (!$request->isPut) {
                return General::generalMethod($request, 405, [], $this, Constants::$PUT_METHOD_NOT_ALLOWED);
            }
            $data = $request->bodyParams;
            $data = [
                'id' => array_key_exists('id', $data) ? (int)trim($data['id']) : 0,
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'name' => array_key_exists('name', $data) ? trim($data['name']) : '',
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
                'units_measurement_volume_id' => array_key_exists('units_measurement_volume_id', $data) ? (int)($data['units_measurement_volume_id']) : 0,
            ];
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (empty($data['name'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_NAME_OF_THE_TYPE_OF_MATERIAL);
            }
            if (mb_strlen($data['name']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_OF_THE_NAME_OF_THE_TYPE_OF_MATERIAL_CAN_BE_100_CHARACTERS);
            }
            if (!is_int($data['id']) || $data['id'] <= 0 || !is_int($data['user_id']) || $data['user_id'] <= 0
                || !is_int($data['units_measurement_volume_id']) || $data['units_measurement_volume_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!MaterialTypes::checkIsNotExitsId($data['id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$TYPE_MATERIAL_WITH_ID_NOT_FOUND);
            }
            if (!UnitsMeasurementVolume::checkUnit($data['units_measurement_volume_id'])) {
                return General::generalMethod($request, 404, [], $this, Constants::$UNITS_VOLUME_NOT_FOUND);
            }
            if (MaterialTypes::checkIsNotExitsName($data['name'])) {
                return General::generalMethod($request, 400, [], $this, Constants::$TYPE_MATERIAL_WITH_NAME_ALREADY_EXISTS);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $result = MaterialTypes::updateType($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::success(['id' => $data['id'], 'name' => $data['name']], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}