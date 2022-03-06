<?php

namespace app\controllers\api;

use app\models\LegalEntities;
use app\models\MaterialTypes;
use app\models\Payments;
use app\models\Users;
use app\models\Vendors;
use Constants;
use DateTime;
use general\General;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\web\Response;

class PaymentsController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Get(
     *     path="/api/payments/get-all",
     *     summary="Получение списка оплаты",
     *     operationId="get-all",
     *     tags={"payments"},
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
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Смещение",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example={"1":0}
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
     *                          "payments": {
     *                              "Организация 1": {
     *                                  "id": 1,
     *                                  "amount": 123444,
     *                                  "created_at": 1635843654,
     *                                  "icon": "algolia",
     *                                  "legal_entities_type_id": 1,
     *                                  "legal_entity": "Организация 1",
     *                                  "legal_entity_id": 1,
     *                                  "legal_entity_type": "ОАО",
     *                                  "prefix": "fab",
     *                                  "vendor": "Поставщик 1",
     *                                  "vendor_id": 1,
     *                                  "material_type": "Тип материала 1",
     *                                  "material_type_id": 1,
     *                                  "units_measurement_volume_id": 1,
     *                              }
     *                          },
     *                          "legal_entity_ids": {
     *                              "Организация 1": 1
     *                          },
     *                          "count": {
     *                              "Организация 1": 1
     *                          }
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
     *     )
     * )
     */
    function actionGetAll($token = '', $user_id = 0, $limit = 0, $offset = []): Response
    {
        try {
            $request = Yii::$app->request;
            if (!$request->isGet) {
                return General::generalMethod($request, 405, [], $this, Constants::$GET_METHOD_NOT_ALLOWED);
            }
            $token = trim($token);
            $user_id = (int)$user_id;
            $limit = (int)$limit;
            if (!empty($offset)) {
                $tmp_offset = [];
                foreach (json_decode($offset) as $key => $value) {
                    $tmp_offset[$key] = (int)$value;
                }
                $offset = $tmp_offset;
            }
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
            foreach ($offset as $value) {
                if ($value < 0) {
                    return General::generalMethod($request, 400, [$offset], $this, Constants::$OFFSET_MUST_BE_GREATER_THAN_ZERO);
                }
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $legal_entities = LegalEntities::getAll();
            $payments = [];
            $payments_count = [];
            $legal_entity_ids = [];
            foreach ($legal_entities as $legal_entity) {
                $name = null;
                $payments_tmp = Payments::getAll($limit, 0, $legal_entity['id']);
                if (array_key_exists($legal_entity['id'], $offset)) {
                    $payments_tmp = Payments::getAll($limit, $offset[$legal_entity['id']], $legal_entity['id']);
                }
                foreach ($payments_tmp as $payment) {
                    if ($payment->vendor['is_archive'] === 0) {
                        $name = $legal_entity_name = $legal_entity->type['name'] . ' ' . $legal_entity['name'];
                        $payments[$legal_entity_name][] = [
                            'id' => $payment['id'],
                            'vendor_id' => $payment['vendor_id'],
                            'vendor' => $payment->vendor['name'],
                            'icon' => $payment->vendor->icon['name'],
                            'prefix' => $payment->vendor->icon['prefix'],
                            'amount' => $payment['amount'],
                            'created_at' => $payment['created_at'],
                            'legal_entity_id' => $payment['legal_entity_id'],
                            'legal_entity' => $payment->legalEntity['name'],
                            'legal_entity_type' => $payment->legalEntity->type['name'],
                            'legal_entities_type_id' => $payment->legalEntity['id'],
                            'material_type' => $payment->materialType['name'],
                            'material_type_id' => $payment->materialType['id'],
                            'units_measurement_volume_id' => $payment->materialType['units_measurement_volume_id'],
                        ];
                    }
                }
                if ($name) {
                    $payments_count[$name] = Payments::getAllCount($legal_entity['id']);
                    $legal_entity_ids[$name] = $legal_entity['id'];
                }
            }
            return General::success(['payments' => $payments ?: [], 'count' => $payments_count ?: [], 'legal_entity_ids' => $legal_entity_ids ?: []], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/payments/create",
     *     summary="Создание нового платежа",
     *     operationId="create",
     *     tags={"payments"},
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
     *                     property="vendor_id",
     *                     description="ID поставщика",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="legal_entity_id",
     *                     description="ID юридического лица",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="material_type_id",
     *                     description="ID типа материала",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     description="Сумма оплаты",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     description="Дата и время оплаты",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="ID пользователя",
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
     *                     "message": "Новый платеж успешно создан"
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
     *                 example="Сумма должна быть целым числом и должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Сумма должна быть целым числом и должен быть больше нуля"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Сумма должна быть меньше 1 000 000 000 рублей",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Сумма должна быть меньше 1 000 000 000 рублей"
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
     *                 example="Поставщик с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Поставщик с указанным идентификатором не найден"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Юридическое лицо с указанным идентификатором не найдено",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Юридическое лицо с указанным идентификатором не найдено"
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
            $date = new DateTime();
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'vendor_id' => array_key_exists('vendor_id', $data) ? (int)($data['vendor_id']) : 0,
                'legal_entity_id' => array_key_exists('legal_entity_id', $data) ? (int)($data['legal_entity_id']) : 0,
                'material_type_id' => array_key_exists('material_type_id', $data) ? (int)($data['material_type_id']) : 0,
                'amount' => array_key_exists('amount', $data) ? (float)($data['amount']) : 0.0,
                'created_at' => array_key_exists('created_at', $data) ? (int)($data['created_at']) : $date->getTimestamp(),
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
            ];
            if ($data['created_at'] === 0) {
                $data['created_at'] = $date->getTimestamp();
            }
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!is_int($data['vendor_id']) || $data['vendor_id'] <= 0 || !is_int($data['legal_entity_id'])
                || $data['legal_entity_id'] <= 0 || !is_int($data['user_id']) || $data['user_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!is_float($data['amount']) || $data['amount'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO);
            }
            if (!($data['amount'] < 1000000000)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$AMOUNT_MUST_LESS_THAN_1000000000RUBLES);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Vendors::checkIsNotExitsId($data['vendor_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!LegalEntities::checkIsNotExitsId($data['legal_entity_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$LEGAL_ENTITY_WITH_ID_NOT_FOUND);
            }
            if (!MaterialTypes::checkIsNotExitsId($data['material_type_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $result = Payments::savePayment($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 201, $data, $this, Constants::$NEW_PAYMENT_CREATED_SUCCESSFULLY);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/payments/update",
     *     summary="Обновление платежа",
     *     operationId="update",
     *     tags={"payments"},
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
     *                     property="vendor_id",
     *                     description="ID поставщика",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID платежа",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="legal_entity_id",
     *                     description="ID юридического лица",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="material_type_id",
     *                     description="ID типа материала",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     description="Сумма оплаты",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     description="Дата и время оплаты",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="ID пользователя",
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
     *                          "legal_entity_id": 1,
     *                          "material_type_id": 1,
     *                          "vendor_id": 1,
     *                          "created_at": 1635843654,
     *                          "amount": 450000
     *                     }
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
     *                 example="Сумма должна быть целым числом и должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Сумма должна быть целым числом и должен быть больше нуля"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Сумма должна быть меньше 1 000 000 000 рублей",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Сумма должна быть меньше 1 000 000 000 рублей"
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
     *                 example="Поставщик с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Поставщик с указанным идентификатором не найден"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Юридическое лицо с указанным идентификатором не найдено",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Юридическое лицо с указанным идентификатором не найдено"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Платежа с указанным идентификатором не найдено",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Платежа с указанным идентификатором не найдено"
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
     *                 example="Пожалуйста, используйте метод PUT для этого запроса",
     *                 summary="",
     *                 value={
     *                     "code": 405,
     *                     "status": "Method Not Allowed",
     *                     "message": "Пожалуйста, используйте метод PUT для этого запроса"
     *                  }
     *              )
     *          )
     *     )
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
            $date = new DateTime();
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'id' => array_key_exists('id', $data) ? (int)($data['id']) : 0,
                'vendor_id' => array_key_exists('vendor_id', $data) ? (int)($data['vendor_id']) : 0,
                'legal_entity_id' => array_key_exists('legal_entity_id', $data) ? (int)($data['legal_entity_id']) : 0,
                'material_type_id' => array_key_exists('material_type_id', $data) ? (int)($data['material_type_id']) : 0,
                'amount' => array_key_exists('amount', $data) ? (float)($data['amount']) : 0.0,
                'created_at' => array_key_exists('created_at', $data) ? (int)($data['created_at']) : $date->getTimestamp(),
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
            ];
            if ($data['created_at'] === 0) {
                $data['created_at'] = $date->getTimestamp();
            }
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!is_int($data['vendor_id']) || $data['vendor_id'] <= 0 || !is_int($data['legal_entity_id']) || $data['legal_entity_id'] <= 0
                || !is_int($data['user_id']) || $data['user_id'] <= 0 || !is_int($data['id']) || $data['id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!is_float($data['amount']) || $data['amount'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO);
            }
            if (!($data['amount'] < 1000000000)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$AMOUNT_MUST_LESS_THAN_1000000000RUBLES);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Vendors::checkIsNotExitsId($data['vendor_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!LegalEntities::checkIsNotExitsId($data['legal_entity_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$LEGAL_ENTITY_WITH_ID_NOT_FOUND);
            }
            if (!LegalEntities::checkIsNotExitsId($data['material_type_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
            }
            if (!Payments::checkIsNotExitsId($data['id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_PAYMENT_WITH_ID_WAS_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $result = Payments::updatePayment($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::success([
                'id' => $data['id'],
                'vendor_id' => $data['vendor_id'],
                'legal_entity_id' => $data['legal_entity_id'],
                'material_type_id' => $data['material_type_id'],
                'amount' => $data['amount'],
                'created_at' => $data['created_at'],
            ], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/payments/delete",
     *     summary="Удаление платежа",
     *     operationId="delete",
     *     tags={"payments"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID платежа",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     description="Токен пользователя",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="ID пользователя",
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
     *                     "message": "Платеж успешно удален"
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
     *                 example="Платежа с указанным идентификатором не найдено",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Платежа с указанным идентификатором не найдено"
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
     *                 example="Пожалуйста, используйте метод DELETE для этого запроса",
     *                 summary="",
     *                 value={
     *                     "code": 405,
     *                     "status": "Method Not Allowed",
     *                     "message": "Пожалуйста, используйте метод DELETE для этого запроса"
     *                  }
     *              )
     *          )
     *     )
     * )
     */
    function actionDelete(): Response
    {
        try {
            $request = Yii::$app->request;
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }
            if (!$request->isDelete) {
                return General::generalMethod($request, 405, [], $this, Constants::$DELETE_METHOD_NOT_ALLOWED);
            }
            $data = $request->bodyParams;
            $id = array_key_exists('id', $data) ? (int)trim($data['id']) : 0;
            $token = array_key_exists('token', $data) ? trim($data['token']) : '';
            $user_id = array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0;
            if (empty($token)) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!is_int($id) || $id <= 0 || !is_int($user_id) || $user_id <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Payments::checkIsNotExitsId($id)) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_PAYMENT_WITH_ID_WAS_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            if (!Payments::deletePayment($data['id'])) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 200, $data, $this, Constants::$PAYMENT_SUCCESSFULLY_REMOVED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}