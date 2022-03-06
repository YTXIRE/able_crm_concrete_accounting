<?php

namespace app\controllers\api;

use app\models\Files;
use app\models\HistoryOperation;
use app\models\LegalEntities;
use app\models\Materials;
use app\models\Objects;
use app\models\Users;
use app\models\Vendors;
use Constants;
use DateTime;
use general\General;
use Yandex\Disk\DiskClient;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class HistoryOperationController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Get(
     *     path="/api/history-operation/get-all-operations-by-material",
     *     summary="Получение списка оплаты",
     *     operationId="get-all-operations-by-material",
     *     tags={"history-operation"},
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
     *     @OA\Parameter(
     *         name="vendor_id",
     *         in="query",
     *         description="ID поставщика",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="object_id",
     *         in="query",
     *         description="ID объекта",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="material_id",
     *         in="query",
     *         description="ID материала",
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
     *
     *                      }
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверные данные",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="Пожалуйста, укажите токен пользователя",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, укажите токен пользователя"
     *                  }
     *              ),
     *             @OA\Examples(
     *                 example="Максимальная длина токена может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина токена может быть 100 символов"
     *                  }
     *              ),
     *             @OA\Examples(
     *                 example="Идентификатор должен быть целым числом и должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Идентификатор должен быть целым числом и должен быть больше нуля"
     *                  }
     *              ),
     *             @OA\Examples(
     *                 example="Лимит должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Лимит должен быть больше нуля"
     *                  }
     *              ),
     *             @OA\Examples(
     *                 example="Смещение должно быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Смещение должно быть больше нуля"
     *                  }
     *              ),
     *         )
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
     *                 example="Объект с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Объект с указанным идентификатором не найден"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Материал с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Материал с указанным идентификатором не найден"
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
    public function actionGetAllOperationsByMaterial($token = '', $user_id = 0, $vendor_id = 0, $object_id = 0, $material_id = 0, $limit = 0, $offset = 0): Response
    {
        try {
            $request = Yii::$app->request;
            if (!$request->isGet) {
                return General::generalMethod($request, 405, [], $this, Constants::$GET_METHOD_NOT_ALLOWED);
            }
            $token = trim($token);
            $user_id = (int)$user_id;
            $vendor_id = (int)$vendor_id;
            $object_id = (int)$object_id;
            $material_id = (int)$material_id;
            $limit = (int)$limit;
            $offset = (int)$offset;
            if (empty($token)) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!is_int($user_id) || $user_id <= 0 || !is_int($vendor_id) || $vendor_id <= 0 || !is_int($object_id)
                || $object_id <= 0 || !is_int($material_id) || $material_id <= 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
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
            if (!Vendors::checkIsNotExitsId($vendor_id)) {
                return General::generalMethod($request, 404, [], $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!Objects::checkIsNotExitsId($object_id)) {
                return General::generalMethod($request, 404, [], $this, Constants::$NO_OBJECT_WITH_ID_WAS_FOUND);
            }
            if (!Materials::checkIsNotExitsId($material_id)) {
                return General::generalMethod($request, 404, [], $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $history_operations = [
                'operations' => [],
                'count' => [],
            ];
            $history = HistoryOperation::getAll($vendor_id, $material_id, $object_id, $limit, $offset);
            foreach ($history as $value) {
                if ($value->vendor['is_archive'] === 0 && $value->object['is_archive'] === 0) {
                    if ($value['file_id']) {
                        $file = [
                            'id' => $value['file_id'],
                            'link' => $value->file['filename'],
                            'name' => $value->file['name'],
                        ];
                    } else {
                        $file = [
                            'id' => null,
                            'link' => null,
                            'name' => null,
                        ];
                    }
                    $history_operations['operations'][] = [
                        'id' => $value['id'],
                        'vendor' => [
                            'id' => $value['vendor_id'],
                            'name' => $value->vendor['name'],
                            'icon' => $value->vendor->icon['name'],
                            'prefix' => $value->vendor->icon['prefix'],
                        ],
                        'object' => [
                            'id' => $value['object_id'],
                            'name' => $value->object['name'],
                        ],
                        'material' => [
                            'id' => $value['material_id'],
                            'name' => $value->material['name'],
                            'type' => $value->material->materialType['name'],
                            'type_id' => $value->material->materialType['id'],
                            'units' => $value->material->materialType->units['name']
                        ],
                        'legal_entity' => [
                            "id" => $value->legalEntity['id'],
                            "legal_entities_type_id" => $value->legalEntity['legal_entities_type_id'],
                            "legal_entities_type" => $value->legalEntity->type['name'],
                            "name" => $value->legalEntity['name'],
                        ],
                        'volume' => (float)$value['volume'],
                        'created_at' => $value['created_at'],
                        'confirmed_data' => $value['confirmed_data'],
                        'price' => (float)$value['price'],
                        'total' => (float)$value['total'],
                        'file' => $file,
                        'comment' => $value['comment']
                    ];
                    $history_operations['count'] = (int)HistoryOperation::getAllCount($vendor_id, $material_id, $object_id);
                }
            }
            return General::success($history_operations ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/history-operation/get-objects-by-vendor",
     *     summary="Получение списка объектов у поставщика",
     *     operationId="get-objects-by-vendor",
     *     tags={"history-operation"},
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
     *     @OA\Parameter(
     *         name="vendor_id",
     *         in="query",
     *         description="ID поставщика",
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
     *                          "Объект 1": {
     *                              "total": 50000,
     *                              "object_id": 1
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
     *             mediaType="application/json",
     *             @OA\Examples(
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
     *         )
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
    public function actionGetObjectsByVendor($token = '', $user_id = 0, $vendor_id = 0): Response
    {
        try {
            $request = Yii::$app->request;
            if (!$request->isGet) {
                return General::generalMethod($request, 405, [], $this, Constants::$GET_METHOD_NOT_ALLOWED);
            }
            $token = trim($token);
            $user_id = (int)$user_id;
            $vendor_id = (int)$vendor_id;
            if (empty($token)) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!is_int($user_id) || $user_id <= 0 || !is_int($vendor_id) || $vendor_id <= 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Vendors::checkIsNotExitsId($vendor_id)) {
                return General::generalMethod($request, 404, [], $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $objects = [];
            foreach (HistoryOperation::getAllObjectsByVendor($vendor_id) as $value) {
                if ($value->object['is_archive'] === 0) {
                    if (array_key_exists($value->object['name'], $objects)) {
                        $objects[$value->object['name']]['total'] += $value['total'];
                        $objects[$value->object['name']]['object_id'] = $value->object['id'];
                    } else {
                        $objects[$value->object['name']] = [
                            "total" => $value['total'],
                            "object_id" => $value->object['id']
                        ];
                    }
                }
            }
            return General::success($objects ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/history-operation/get-material-by-object",
     *     summary="Получение списка материалов в объекте",
     *     operationId="get-material-by-object",
     *     tags={"history-operation"},
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
     *     @OA\Parameter(
     *         name="vendor_id",
     *         in="query",
     *         description="ID поставщика",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="object_id",
     *         in="query",
     *         description="ID объекта",
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
     *                          "Материал 1": {
     *                              "total": 50000,
     *                              "material_id": 1
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
     *             mediaType="application/json",
     *             @OA\Examples(
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
     *         )
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
     *                 example="Объект с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Объект с указанным идентификатором не найден"
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
    public function actionGetMaterialByObject($token = '', $user_id = 0, $vendor_id = 0, $object_id = 0): Response
    {
        try {
            $request = Yii::$app->request;
            if (!$request->isGet) {
                return General::generalMethod($request, 405, [], $this, Constants::$GET_METHOD_NOT_ALLOWED);
            }
            $token = trim($token);
            $user_id = (int)$user_id;
            $vendor_id = (int)$vendor_id;
            $object_id = (int)$object_id;
            if (empty($token)) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!is_int($user_id) || $user_id <= 0 || !is_int($vendor_id) || $vendor_id <= 0 || !is_int($object_id) || $object_id <= 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Vendors::checkIsNotExitsId($vendor_id)) {
                return General::generalMethod($request, 404, [], $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!Objects::checkIsNotExitsId($object_id)) {
                return General::generalMethod($request, 404, [], $this, Constants::$NO_OBJECT_WITH_ID_WAS_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $material = [];
            foreach (HistoryOperation::getAllMaterialByObject($vendor_id, $object_id) as $value) {
                if ($value->object['is_archive'] === 0 && $value->vendor['is_archive'] === 0) {
                    if (array_key_exists($value->material['name'], $material)) {
                        $material[$value->material['name']]['total'] += $value['total'];
                        $material[$value->material['name']]['material_id'] = $value['material_id'];
                    } else {
                        $material[$value->material['name']] = [
                            "total" => $value['total'],
                            "material_id" => $value['material_id']
                        ];
                    }
                }
            }
            return General::success($material ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/history-operation/create",
     *     summary="Создание новой операции",
     *     operationId="create",
     *     tags={"history-operation"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
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
     *                 @OA\Property(
     *                     property="vendor_id",
     *                     description="ID поставщика",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="material_id",
     *                     description="ID материала",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="object_id",
     *                     description="ID объекта",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="is_debt",
     *                     description="Долг",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="volume",
     *                     description="Объем",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Цена",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     description="Итоговая стоимость",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="comment",
     *                     description="Комментарий к операции",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     description="Дата операции",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="confirmed_data",
     *                     description="Подтверждение данных",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     description="Документ об операци",
     *                     type="file"
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
     *                     "message": "Новая операция успешно добавлена"
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверные данные",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
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
     *                 example="Пожалуйста, укажите комментарий",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, укажите комментарий"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина комментария может быть 1000 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина комментария может быть 1000 символов"
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
     *                 example="Объем должен быть меньше 1 000 000 000",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Объем должен быть меньше 1 000 000 000"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Объем должен быть целым числом и должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Объем должен быть целым числом и должен быть больше нуля"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Файл имеет не допустимое расширение",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Файл имеет не допустимое расширение"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Вес файла должен быть менее 30 мегабайт",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Вес файла должен быть менее 30 мегабайт"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Ошибка загрузки файла. Попробуйте еще раз",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Ошибка загрузки файла. Попробуйте еще раз"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Подтверждение данных может быть либо подтверждено - 1, либо не подтверждено 0",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Подтверждение данных может быть либо подтверждено - 1, либо не подтверждено 0"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Итог на конец года может быть либо активным - 1, либо выключенным 0",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Итог на конец года может быть либо активным - 1, либо выключенным 0"
     *                  }
     *              )
     *         )
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
     *                 example="Материал с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Материал с указанным идентификатором не найден"
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
     *                 example="Объект с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Объект с указанным идентификатором не найден"
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
            $uploads = UploadedFile::getInstancesByName("file");
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
                'is_debt' => array_key_exists('is_debt', $data) ? (int)($data['is_debt']) : 0,
                'vendor_id' => array_key_exists('vendor_id', $data) ? (int)($data['vendor_id']) : 0,
                'material_id' => array_key_exists('material_id', $data) ? (int)($data['material_id']) : 0,
                'legal_entity_id' => array_key_exists('legal_entity_id', $data) ? (int)($data['legal_entity_id']) : 0,
                'object_id' => array_key_exists('object_id', $data) ? (int)($data['object_id']) : 0,
                'confirmed_data' => array_key_exists('confirmed_data', $data) ? (int)($data['confirmed_data']) : 0,
                'volume' => array_key_exists('volume', $data) ? (float)($data['volume']) : 0.0,
                'price' => array_key_exists('price', $data) ? (float)($data['price']) : 0.0,
                'total' => array_key_exists('total', $data) ? (float)($data['total']) : 0.0,
                'comment' => array_key_exists('comment', $data) ? trim($data['comment']) : '',
                'created_at' => array_key_exists('created_at', $data) ? (int)($data['created_at']) : $date->getTimestamp(),
                'file' => $uploads,
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
            if (mb_strlen($data['comment']) > 1000) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_COMMENT_CAN_BE_1000_CHARACTERS);
            }
            if (!is_int($data['user_id']) || $data['user_id'] <= 0 || !is_int($data['object_id']) || $data['object_id'] <= 0 ||
                !is_int($data['vendor_id']) || $data['vendor_id'] <= 0 || !is_int($data['material_id']) || $data['material_id'] <= 0
                || !is_int($data['legal_entity_id']) || $data['legal_entity_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!is_float($data['price']) || ($data['price'] <= -1 && $data['is_debt'] === 1) ||
                ($data['price'] <= 0 && $data['is_debt'] === 0) || !is_float($data['total']) || $data['total'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO);
            }
            if (!($data['price'] < 1000000000) || !($data['total'] < 1000000000)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$AMOUNT_MUST_LESS_THAN_1000000000RUBLES);
            }
            if (!($data['volume'] < 1000000000)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$VOLUME_MUST_LESS_THAN_1000000000);
            }
            if (!is_float($data['volume']) || ($data['volume'] <= -1 && $data['is_debt'] === 1) || ($data['volume'] <= 0 && $data['is_debt'] === 0)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$VOLUME_MUST_INTEGER_AND_MUST_GREATER_ZERO);
            }
            if ($data['is_debt'] !== 0 && $data['is_debt'] !== 1) {
                return General::generalMethod($request, 400, $data, $this, Constants::$DEBT_ON_OR_OFF);
            }
            if ($data['confirmed_data'] !== 0 && $data['confirmed_data'] !== 1) {
                return General::generalMethod($request, 400, $data, $this, Constants::$CONFIRMATED_DATA_IS_ACTIVE_OR_NO_ACTIVE);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Materials::checkIsNotExitsId($data['material_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
            }
            if (!LegalEntities::checkIsNotExitsId($data['legal_entity_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$LEGAL_ENTITY_WITH_ID_NOT_FOUND);
            }
            if (!Vendors::checkIsNotExitsId($data['vendor_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!Objects::checkIsNotExitsId($data['object_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_OBJECT_WITH_ID_WAS_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $data['file_id'] = null;
            if (count($uploads)) {
                $ext_type = $data['file'][0]->type;
                $ext = explode('/', $data['file'][0]->type)[1];
                $extensions = ['image/png', 'image/jpg', 'image/jpeg', 'application/vnd.oasis.opendocument.text',
                    'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.presentation',
                    'application/vnd.oasis.opendocument.graphics', 'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel.sheet.macroEnabled.12',
                    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/pdf'
                ];
                if (!$ext || in_array("application/$ext", $extensions) || in_array("image/$ext", $extensions)) {
                    $ext = explode('.', $data['file'][0]->name)[1];
                }
                if ($ext_type === '') {
                    if (in_array($ext, ['pdf', 'doc', 'docx', 'docs', 'xls', 'xlsx', 'ppt', 'pptx'])) {
                        $ext_type = "application/$ext";
                    } else {
                        $ext_type = "image/$ext";
                    }
                }
                if (!in_array($ext_type, $extensions)) {
                    return General::generalMethod($request, 400, $data, $this, Constants::$FILE_UNRESOLVED_EXTENSION);
                }
                $file_size = (int)filesize($data['file'][0]->tempName) / 1024;
                if (!($file_size < 5000)) {
                    return General::generalMethod($request, 400, $data, $this, Constants::$FILE_WEIGHT_MUST_BE_LESS_THAN_30_MB);
                }
                $filename = Yii::$app->security->generateRandomString(64);
                $orig_name = null;
                $url = null;
                $file_path = null;
                foreach ($uploads as $file) {
                    $orig_name = $file->name;
                    $path = "@app/web/files/$filename.$ext";
                    $file->saveAs($path);
                    $diskClient = new DiskClient('AQAAAABa2-WoAAeIyNaQO8mc5UrWrgL6qFeu3_s');
                    $diskClient->setServiceScheme(DiskClient::HTTPS_SCHEME);
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/web/files/$filename.$ext";
                    $diskClient->uploadFile(
                        '/CRM_docs/',
                        [
                            'path' => $file_path,
                            'size' => $file_path,
                            'name' => "$filename.$ext"
                        ]
                    );
                    $url = $diskClient->startPublishing("/CRM_docs/$filename.$ext");
                }
                $result = Files::saveFile($url, $data['user_id'], 'document', $orig_name);
                if (!$result) {
                    return General::generalMethod($request, 400, $data, $this, Constants::$ERROR_UPLOAD_FILE);
                }
                $data['file_id'] = Files::getFileWithName($url)['id'];
                unlink($file_path);
            }
            $result = HistoryOperation::create($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 201, [], $this, Constants::$SUCCESS_OPERATION_CREATED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/history-operation/update",
     *     summary="Обновление операции",
     *     operationId="update",
     *     tags={"history-operation"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
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
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID операции",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="vendor_id",
     *                     description="ID поставщика",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="material_id",
     *                     description="ID материала",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="object_id",
     *                     description="ID объекта",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="volume",
     *                     description="Объем",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="Цена",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     description="Итоговая стоимость",
     *                     type="number"
     *                 ),
     *                 @OA\Property(
     *                     property="comment",
     *                     description="Комментарий к операции",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     description="Дата операции",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="confirmed_data",
     *                     description="Подтверждение данных",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="file",
     *                     description="Документ об операци",
     *                     type="file"
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
     *                     "message": "Операция успешно обновлена"
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверные данные",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
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
     *                 example="Пожалуйста, укажите комментарий",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, укажите комментарий"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина комментария может быть 1000 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина комментария может быть 1000 символов"
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
     *                 example="Объем должен быть меньше 1 000 000 000",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Объем должен быть меньше 1 000 000 000"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Объем должен быть целым числом и должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Объем должен быть целым числом и должен быть больше нуля"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Файл имеет не допустимое расширение",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Файл имеет не допустимое расширение"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Вес файла должен быть менее 30 мегабайт",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Вес файла должен быть менее 30 мегабайт"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Ошибка загрузки файла. Попробуйте еще раз",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Ошибка загрузки файла. Попробуйте еще раз"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Подтверждение данных может быть либо подтверждено - 1, либо не подтверждено 0",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Подтверждение данных может быть либо подтверждено - 1, либо не подтверждено 0"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Итог на конец года может быть либо активным - 1, либо выключенным 0",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Итог на конец года может быть либо активным - 1, либо выключенным 0"
     *                  }
     *              )
     *         )
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
     *                 example="Материал с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Материал с указанным идентификатором не найден"
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
     *                 example="Объект с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Объект с указанным идентификатором не найден"
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
     *                 example="Операции с указанным идентификатором не найдено",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Операции с указанным идентификатором не найдено"
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
    function actionUpdate(): Response
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
            $uploads = UploadedFile::getInstancesByName("file");
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'id' => array_key_exists('id', $data) ? (int)($data['id']) : 0,
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
                'vendor_id' => array_key_exists('vendor_id', $data) ? (int)($data['vendor_id']) : 0,
                'material_id' => array_key_exists('material_id', $data) ? (int)($data['material_id']) : 0,
                'legal_entity_id' => array_key_exists('legal_entity_id', $data) ? (int)($data['legal_entity_id']) : 0,
                'confirmed_data' => array_key_exists('confirmed_data', $data) ? (int)($data['confirmed_data']) : 0,
                'object_id' => array_key_exists('object_id', $data) ? (int)($data['object_id']) : 0,
                'volume' => array_key_exists('volume', $data) ? (float)($data['volume']) : 0.0,
                'price' => array_key_exists('price', $data) ? (float)($data['price']) : 0.0,
                'total' => array_key_exists('total', $data) ? (float)($data['total']) : 0.0,
                'comment' => array_key_exists('comment', $data) ? trim($data['comment']) : '',
                'created_at' => array_key_exists('created_at', $data) ? (int)($data['created_at']) : $date->getTimestamp(),
                'file' => $uploads,
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
            if (mb_strlen($data['comment']) > 1000) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_COMMENT_CAN_BE_1000_CHARACTERS);
            }
            if (!is_int($data['user_id']) || $data['user_id'] <= 0 || !is_int($data['object_id']) || $data['object_id'] <= 0 ||
                !is_int($data['vendor_id']) || $data['vendor_id'] <= 0 || !is_int($data['material_id']) || $data['material_id'] <= 0 ||
                !is_int($data['id']) || $data['id'] <= 0 || !is_int($data['legal_entity_id']) || $data['legal_entity_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!is_float($data['price']) || $data['price'] <= -1 || !is_float($data['total']) || $data['total'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO);
            }
            if (!($data['price'] < 1000000000) || !($data['total'] < 1000000000)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$AMOUNT_MUST_LESS_THAN_1000000000RUBLES);
            }
            if (!($data['volume'] < 1000000000)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$VOLUME_MUST_LESS_THAN_1000000000);
            }
            if (!is_float($data['volume']) || $data['volume'] <= -1) {
                return General::generalMethod($request, 400, $data, $this, Constants::$VOLUME_MUST_INTEGER_AND_MUST_GREATER_ZERO);
            }
            if ($data['confirmed_data'] !== 0 && $data['confirmed_data'] !== 1) {
                return General::generalMethod($request, 400, $data, $this, Constants::$CONFIRMATED_DATA_IS_ACTIVE_OR_NO_ACTIVE);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!HistoryOperation::checkIsNotExitsId($data['id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$OPERATION_WITH_ID_NOT_FOUND);
            }
            if (!Materials::checkIsNotExitsId($data['material_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
            }
            if (!Vendors::checkIsNotExitsId($data['vendor_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!LegalEntities::checkIsNotExitsId($data['legal_entity_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$LEGAL_ENTITY_WITH_ID_NOT_FOUND);
            }
            if (!Objects::checkIsNotExitsId($data['object_id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_OBJECT_WITH_ID_WAS_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $data['file_id'] = null;
            if (count($uploads)) {
                $ext_type = $data['file'][0]->type;
                $ext = explode('/', $data['file'][0]->type)[1];
                $extensions = ['image/png', 'image/jpg', 'image/jpeg', 'application/vnd.oasis.opendocument.text',
                    'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.presentation',
                    'application/vnd.oasis.opendocument.graphics', 'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel.sheet.macroEnabled.12',
                    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/pdf'
                ];
                if (!$ext || in_array("application/$ext", $extensions) || in_array("image/$ext", $extensions)) {
                    $ext = explode('.', $data['file'][0]->name)[1];
                }
                if ($ext_type === '') {
                    if (in_array($ext, ['pdf', 'doc', 'docx', 'docs', 'xls', 'xlsx', 'ppt', 'pptx'])) {
                        $ext_type = "application/$ext";
                    } else {
                        $ext_type = "image/$ext";
                    }
                }
                if (!in_array($ext_type, $extensions)) {
                    return General::generalMethod($request, 400, $data, $this, Constants::$FILE_UNRESOLVED_EXTENSION);
                }
                $file_size = (int)filesize($data['file'][0]->tempName) / 1024;
                if (!($file_size < 5000)) {
                    return General::generalMethod($request, 400, $data, $this, Constants::$FILE_WEIGHT_MUST_BE_LESS_THAN_30_MB);
                }
                $filename = Yii::$app->security->generateRandomString(64);
                $orig_name = null;
                $url = null;
                $file_path = null;
                foreach ($uploads as $file) {
                    $orig_name = $file->name;
                    $path = "@app/web/files/$filename.$ext";
                    $file->saveAs($path);
                    $diskClient = new DiskClient('AQAAAABa2-WoAAeIyNaQO8mc5UrWrgL6qFeu3_s');
                    $diskClient->setServiceScheme(DiskClient::HTTPS_SCHEME);
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . "/web/files/$filename.$ext";
                    $diskClient->uploadFile(
                        '/CRM_docs/',
                        [
                            'path' => $file_path,
                            'size' => $file_path,
                            'name' => "$filename.$ext"
                        ]
                    );
                    $url = $diskClient->startPublishing("/CRM_docs/$filename.$ext");
                }
                $result = Files::saveFile($url, $data['user_id'], 'document', $orig_name);
                if (!$result) {
                    return General::generalMethod($request, 400, $data, $this, Constants::$ERROR_UPLOAD_FILE);
                }
                $data['file_id'] = Files::getFileWithName($url)['id'];
                unlink($file_path);
            }
            $result = HistoryOperation::updateOperation($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 200, [], $this, Constants::$SUCCESS_OPERATION_UPDATED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/history-operation/delete",
     *     summary="Удаление операции",
     *     operationId="delete",
     *     tags={"history-operation"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID операции",
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
     *                     "message": "Операция успешно удалена"
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
     *                 example="Операции с указанным идентификатором не найдено",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Операции с указанным идентификатором не найдено"
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
            if (!HistoryOperation::checkIsNotExitsId($id)) {
                return General::generalMethod($request, 404, $data, $this, Constants::$OPERATION_WITH_ID_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            if (!HistoryOperation::deleteOperation($data['id'])) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 200, $data, $this, Constants::$OPERATION_SUCCESS_REMOVED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}