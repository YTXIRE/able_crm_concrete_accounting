<?php

namespace app\controllers\api;

use app\models\Filters;
use app\models\HistoryOperation;
use app\models\LegalEntities;
use app\models\LegalEntitiesTypes;
use app\models\Materials;
use app\models\Objects;
use app\models\Payments;
use app\models\Users;
use app\models\Vendors;
use Constants;
use DateTime;
use Exception;
use general\General;
use Yii;
use yii\helpers\Helpers;
use yii\rest\Controller;

class ReportController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Get(
     *     path="/api/report/get-base",
     *     summary="Получение базового отчета",
     *     operationId="get-base",
     *     tags={"report"},
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
     *                          "Поставщик 1": 5000
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
    public function actionGetBase($token = '', $user_id = 0)
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
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $payments = Payments::getAll();
            $vendors = Vendors::getAll();
            $legal_entities = LegalEntities::getAll();
            $tmp_payments = [];
            foreach ($vendors as $vendor) {
                $tmp_payments[$vendor['id']] = [];
            }
            foreach ($payments as $payment) {
                if ($payment->legalEntity['is_archive'] === 0) {
                    if (array_key_exists($payment['legal_entity_id'], $tmp_payments[$payment['vendor_id']])) {
                        $tmp_payments[$payment['vendor_id']][$payment['legal_entity_id']] += $payment['amount'];
                    } else {
                        $tmp_payments[$payment['vendor_id']][$payment['legal_entity_id']] = $payment['amount'];
                    }
                }
            }
            $tmp_operations = [];
            $history_operations = [];
            $labels = [];
            foreach ($vendors as $vendor) {
                $operations = HistoryOperation::getAllObjectsByVendor($vendor['id']);
                foreach ($operations as $operation) {
                    $date = date('d', $operation['created_at']) . " " .
                        Helpers::getMonth(date('m', $operation['created_at'])) . " " .
                        date('Y', $operation['created_at']);
                    if (!in_array($date, $labels)) {
                        $labels[] = $date;
                    }
                }
            }
            usort($labels, function ($a, $b) {
                return strtotime($b) - strtotime($a);
            });
            $final_labels = [];
            foreach ($labels as $label) {
                $final_labels[$label] = 0;
            }
            foreach ($vendors as $vendor) {
                $operations = HistoryOperation::getAllObjectsByVendor($vendor['id']);
                foreach ($operations as $operation) {
                    if (!array_key_exists($operation->vendor['name'], $history_operations) && $operation->object['is_archive'] === 0) {
                        $history_operations[$operation->vendor['name']] = $final_labels;
                    }
                    $date = date('d', $operation['created_at']) . " " .
                        Helpers::getMonth(date('m', $operation['created_at'])) . " " .
                        date('Y', $operation['created_at']);
                    if (array_key_exists($date, $final_labels) && $operation->object['is_archive'] === 0) {
                        if (array_key_exists($operation['vendor_id'], $tmp_operations) && array_key_exists($operation['legal_entity_id'], $tmp_operations[$operation['vendor_id']])) {
                            $tmp_operations[$operation['vendor_id']][$operation['legal_entity_id']] += (float)$operation['total'];
                        } else {
                            $tmp_operations[$operation['vendor_id']][$operation['legal_entity_id']] = (float)$operation['total'];
                        }
                        $history_operations[$operation->vendor['name']][$date] += $operation['total'];
                    }
                }
            }
            $result = [];
            foreach ($vendors as $vendor) {
                if (array_key_exists($vendor['id'], $tmp_payments) && array_key_exists($vendor['id'], $tmp_operations)) {
                    $take = [];
                    $debt = [];
                    foreach ($legal_entities as $legal_entity) {
                        $name = $legal_entity->type['name'] . ' ' . $legal_entity['name'];
                        if (array_key_exists($legal_entity['id'], $tmp_operations[$vendor['id']])) {
                            $take[$name] = $tmp_operations[$vendor['id']][$legal_entity['id']];
                        }
                        if (array_key_exists($legal_entity['id'], $tmp_operations[$vendor['id']])
                            && array_key_exists($legal_entity['id'], $tmp_payments[$vendor['id']])) {
                            $debt[$name] = $tmp_operations[$vendor['id']][$legal_entity['id']] - $tmp_payments[$vendor['id']][$legal_entity['id']];
                        } else {
                            if (array_key_exists($legal_entity['id'], $tmp_operations[$vendor['id']])) {
                                $debt[$name] = $tmp_operations[$vendor['id']][$legal_entity['id']];
                            }
                        }
                    }
                    $result[$vendor['name']] = [
                        'Взяли' => $take,
                        'Долг' => $debt
                    ];
                }
            }
            return General::success($result ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/report/get-advanced",
     *     summary="Получение расширенного отчета",
     *     operationId="get-advanced",
     *     tags={"report"},
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
     *         name="filters",
     *         in="query",
     *         description="Фильтры",
     *         required=true,
     *         @OA\Schema(
     *             type="object"
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
     *                          "Поставщик 1": 5000
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
    public function actionGetAdvanced()
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
                'user_id' => array_key_exists('user_id', $data) ? (int)trim($data['user_id']) : 0,
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'filters' => array_key_exists('filters', $data) ? (array)json_decode($data['filters'], true) : [],
            ];
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, [], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, [], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (count($data['filters']) === 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$SPECIFY_FILTERS);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            foreach ($data['filters'] as $filter) {
                if (!array_key_exists('field', $filter) || !array_key_exists('operation', $filter) || !array_key_exists('value', $filter)
                    || !array_key_exists('unity', $filter)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$FILTER_FIELDS_ARE_MISSING);
                }
            }
            $field_types = [
                'vendor', 'object', 'legal_entity', 'material',
                'material_type', 'price', 'total', 'volume',
                'date_from', 'date_to', 'confirm_history_operation'
            ];
            foreach ($data['filters'] as $filter) {
                if (!in_array($filter['field'], $field_types)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$INVALID_FILTER_TYPE);
                }
            }
            $operation_types = ['equal', 'not_equal', 'over', 'over_or_equal', 'less', 'less_or_equal'];
            foreach ($data['filters'] as $filter) {
                if (!in_array($filter['operation'], $operation_types)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$INVALID_OPERATION_TYPE);
                }
            }
            $fields = [];
            foreach ($data['filters'] as $filter) {
                if (($filter['field'] === 'vendor' || $filter['field'] === 'object' || $filter['field'] === 'legal_entity' ||
                        $filter['field'] === 'material' || $filter['field'] === 'material_type')
                    && (!is_int($filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
                }
                if ($filter['field'] === 'vendor' && !Vendors::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
                }
                if ($filter['field'] === 'object' && !Objects::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$NO_OBJECT_WITH_ID_WAS_FOUND);
                }
                if ($filter['field'] === 'legal_entity' && !LegalEntities::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND);
                }
                if ($filter['field'] === 'material' && !Materials::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
                }
                if ($filter['field'] === 'material_type' && !LegalEntitiesTypes::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$TYPE_ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND);
                }
                if (($filter['field'] === 'price' || $filter['field'] === 'total') && (!is_float((float)$filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO);
                }
                if ($filter['field'] === 'volume' && (!is_float((float)$filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$VOLUME_MUST_INTEGER_AND_MUST_GREATER_ZERO);
                }
                if (($filter['field'] === 'price' || $filter['field'] === 'total') && (!($filter['value'] < 1000000000))) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$AMOUNT_MUST_LESS_THAN_1000000000RUBLES);
                }
                if ($filter['field'] === 'volume' && !($filter['value'] < 1000000000)) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$VOLUME_MUST_LESS_THAN_1000000000);
                }
                if ($filter['field'] === 'confirm_history_operation' && $filter['value'] !== 0 && $filter['value'] !== 1) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$CONFIRMATED_DATA_IS_ACTIVE_OR_NO_ACTIVE);
                }
                if (($filter['field'] === 'date_from' || $filter['field'] === 'date_to') && (!is_int($filter['value']))) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$DATE_MUST_BE_INTEGER);
                }
                if ($filter['field'] === 'date_from' && $filter['value'] === 0) {
                    $filter['date_from'] = $date->getTimestamp();
                }
                if ($filter['field'] === 'date_to' && $filter['value'] === 0) {
                    $filter['date_to'] = $date->getTimestamp();
                }
                if (mb_strtolower($filter['unity']) !== 'or' && mb_strtolower($filter['unity']) !== 'and') {
                    $filter['unity'] = 'and';
                } else {
                    $filter['unity'] = mb_strtolower($filter['unity']);
                }
                $fields[] = $filter['field'];
            }
            if (in_array('material', $fields) && in_array('material_type', $fields)) {
                return General::generalMethod($request, 400, [], $this, Constants::$FILTER_NOT_INCLUDES_MATERIAL_AND_MATERIAL_TYPES_IN_PARALLEL);
            }
            $operations_indication = [
                'equal' => '=',
                'not_equal' => '!=',
                'over' => '>',
                'over_or_equal' => '>=',
                'less' => '<',
                'less_or_equal' => '<='
            ];
            $operations = [];
            $legal_entity_filters = [];
            foreach ($data['filters'] as $filter) {
                switch ($filter['field']) {
                    case 'vendor':
                        $operations[] = [
                            'field' => 'vendor_id',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'object':
                        $operations[] = [
                            'field' => 'object_id',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'material':
                        $operations[] = [
                            'field' => 'material_id',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'price':
                        $operations[] = [
                            'field' => 'price',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'volume':
                        $operations[] = [
                            'field' => 'volume',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'total':
                        $operations[] = [
                            'field' => 'total',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'confirm_history_operation':
                        $operations[] = [
                            'field' => 'confirmed_data',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'date_to':
                    case 'date_from':
                        $operations[] = [
                            'field' => 'created_at',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'material_type':
                        $value = Materials::getMaterialsByType($filter['value']);
                        $tmp_value = [];
                        foreach ($value as $item) {
                            $tmp_value[] = $item['id'];
                        }
                        $operation_filter = $filter['operation'] === 'equal' ? 'in' : 'not in';
                        if (count($value) === 0) {
                            $tmp_value = [1];
                            $operation_filter = 'in';
                        }
                        $operations[] = [
                            'field' => 'material_id',
                            'value' => $tmp_value,
                            'operation' => $operation_filter,
                            'unity' => $filter['unity']
                        ];
                        break;
                    case 'legal_entity':
                        $legal_entity_filters = [
                            'field' => 'legal_entity',
                            'value' => $filter['value'],
                            'operation' => $operations_indication[$filter['operation']],
                            'unity' => $filter['unity']
                        ];
                        break;
                }
            }
            $history_operations = [];
            $history = HistoryOperation::getOperationsByField($operations);
            foreach ($history as $value) {
                if ($value->vendor['is_archive'] === 0 && $value->object['is_archive'] === 0) {
                    if (count($legal_entity_filters) && $legal_entity_filters['value'] === $value->legalEntity['id']) {
                        $history_operations = $this->getOperations($value, $file, $history_operations);
                    }
                    if (!count($legal_entity_filters)) {
                        $history_operations = $this->getOperations($value, $file, $history_operations);
                    }
                }
            }
            return General::success($history_operations ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/report/create-filter",
     *     summary="Создание фильтра",
     *     operationId="create-filter",
     *     tags={"report"},
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
     *                     description="Название поставщика",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="filters",
     *                     description="Фильтры",
     *                     type="object"
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
     *                          "Поставщик 1": 5000
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
    public function actionCreateFilter()
    {
        try {
            $request = Yii::$app->request;
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }
            if (!$request->isPost) {
                return General::generalMethod($request, 405, [], $this, Constants::$POST_METHOD_NOT_ALLOWED);
            }
            $date = new DateTime();
            $data = $request->bodyParams;
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'name' => array_key_exists('name', $data) ? trim($data['name']) : '',
                'filters' => array_key_exists('filters', $data) ? (array)json_decode($data['filters'], true) : [],
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
            ];
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (empty($data['name'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_NAME_FILTER);
            }
            if (mb_strlen($data['name']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_NAME_100_CHARACTERS);
            }
            if (!is_int($data['user_id']) || $data['user_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            foreach ($data['filters'] as $filter) {
                if (!array_key_exists('field', $filter) || !array_key_exists('operation', $filter) || !array_key_exists('value', $filter)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$FILTER_FIELDS_ARE_MISSING);
                }
            }
            $field_types = [
                'vendor', 'object', 'legal_entity', 'material',
                'material_type', 'price', 'total', 'volume',
                'date_from', 'date_to', 'confirm_history_operation'
            ];
            foreach ($data['filters'] as $filter) {
                if (!in_array($filter['field'], $field_types)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$INVALID_FILTER_TYPE);
                }
            }
            $operation_types = ['equal', 'not_equal', 'over', 'over_or_equal', 'less', 'less_or_equal'];
            foreach ($data['filters'] as $filter) {
                if (!in_array($filter['operation'], $operation_types)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$INVALID_OPERATION_TYPE);
                }
            }
            $fields = [];
            foreach ($data['filters'] as $filter) {
                if (($filter['field'] === 'vendor' || $filter['field'] === 'object' || $filter['field'] === 'legal_entity' ||
                        $filter['field'] === 'material' || $filter['field'] === 'material_type')
                    && (!is_int($filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
                }
                if ($filter['field'] === 'vendor' && !Vendors::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
                }
                if ($filter['field'] === 'object' && !Objects::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$NO_OBJECT_WITH_ID_WAS_FOUND);
                }
                if ($filter['field'] === 'legal_entity' && !LegalEntities::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND);
                }
                if ($filter['field'] === 'material' && !Materials::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
                }
                if ($filter['field'] === 'material_type' && !LegalEntitiesTypes::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$TYPE_ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND);
                }
                if (($filter['field'] === 'price' || $filter['field'] === 'total') && (!is_float((float)$filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO);
                }
                if ($filter['field'] === 'volume' && (!is_float((float)$filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$VOLUME_MUST_INTEGER_AND_MUST_GREATER_ZERO);
                }
                if (($filter['field'] === 'price' || $filter['field'] === 'total') && (!($filter['value'] < 1000000000))) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$AMOUNT_MUST_LESS_THAN_1000000000RUBLES);
                }
                if ($filter['field'] === 'volume' && !($filter['value'] < 1000000000)) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$VOLUME_MUST_LESS_THAN_1000000000);
                }
                if ($filter['field'] === 'confirm_history_operation' && (int)$filter['value'] !== 0 &&
                    (int)$filter['value'] !== 1) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$CONFIRMATED_DATA_IS_ACTIVE_OR_NO_ACTIVE);
                }
                if (($filter['field'] === 'date_from' || $filter['field'] === 'date_to') && (!is_int($filter['value']) || !is_int($filter['value']))) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$DATE_MUST_BE_INTEGER);
                }
                if ($filter['field'] === 'date_from' && $filter['value'] === 0) {
                    $filter['date_from'] = $date->getTimestamp();
                }
                if ($filter['field'] === 'date_to' && $filter['value'] === 0) {
                    $filter['date_to'] = $date->getTimestamp();
                }
                $fields[] = $filter['field'];
            }
            if (in_array('material', $fields) && in_array('material_type', $fields)) {
                return General::generalMethod($request, 400, [], $this, Constants::$FILTER_NOT_INCLUDES_MATERIAL_AND_MATERIAL_TYPES_IN_PARALLEL);
            }
            $result = Filters::saveFilters($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 201, $data, $this, Constants::$NEW_FILTER_SUCCESSFULLY_CREATED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/report/get-filters",
     *     summary="Получение фильтров",
     *     operationId="get-filters",
     *     tags={"report"},
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
     *                          "Поставщик 1": 5000
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
    public function actionGetFilters($token = '', $user_id = 0)
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
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $filters = Filters::getAll();
            $result = [];
            foreach ($filters as $filter) {
                $result[] = [
                    'id' => $filter['id'],
                    'name' => $filter['name'],
                    'filters' => json_decode($filter['filter']),
                ];
            }
            return General::success($result ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/report/delete-fliter",
     *     summary="Удаление фильтра",
     *     operationId="delete-fliter",
     *     tags={"report"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID фильтра",
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
     *                     "message": "Фильтр успешно удален"
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
     *                 example="Поставщик с указанным идентификатором уже был удален",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Поставщик с указанным идентификатором уже был удален"
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
    public function actionDeleteFilter()
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
            if (!is_int($id) || $id <= 0 || !is_int($user_id) || $user_id <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Filters::checkIsNotExitsId($id)) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            if (!Filters::deleteFilter($id)) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 200, $data, $this, Constants::$FILTER_SUCCESSFULLY_REMOVED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/report/update-filter",
     *     summary="Обновление фильтра",
     *     operationId="update-filter",
     *     tags={"report"},
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
     *                     description="Название поставщика",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="filters",
     *                     description="Фильтры",
     *                     type="object"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     description="ID пользователя",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID фильтра",
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
     *                     "message": "Фильтр успешно обновлен"
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
    public function actionUpdateFilter()
    {
        try {
            $request = Yii::$app->request;
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }
            if (!$request->isPut) {
                return General::generalMethod($request, 405, [], $this, Constants::$PUT_METHOD_NOT_ALLOWED);
            }
            $date = new DateTime();
            $data = $request->bodyParams;
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'name' => array_key_exists('name', $data) ? trim($data['name']) : '',
                'filters' => array_key_exists('filters', $data) ? (array)json_decode($data['filters'], true) : [],
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
                'id' => array_key_exists('user_id', $data) ? (int)($data['id']) : 0,
            ];
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (empty($data['name'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_NAME_FILTER);
            }
            if (mb_strlen($data['name']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_NAME_100_CHARACTERS);
            }
            if (!is_int($data['user_id']) || $data['user_id'] <= 0 || !is_int($data['id']) || $data['id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Filters::checkIsNotExitsId($data['id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            foreach ($data['filters'] as $filter) {
                if (!array_key_exists('field', $filter) || !array_key_exists('operation', $filter) || !array_key_exists('value', $filter)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$FILTER_FIELDS_ARE_MISSING);
                }
            }
            $field_types = [
                'vendor', 'object', 'legal_entity', 'material',
                'material_type', 'price', 'total', 'volume',
                'date_from', 'date_to', 'confirm_history_operation'
            ];
            foreach ($data['filters'] as $filter) {
                if (!in_array($filter['field'], $field_types)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$INVALID_FILTER_TYPE);
                }
            }
            $operation_types = ['equal', 'not_equal', 'over', 'over_or_equal', 'less', 'less_or_equal'];
            foreach ($data['filters'] as $filter) {
                if (!in_array($filter['operation'], $operation_types)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$INVALID_OPERATION_TYPE);
                }
            }
            $fields = [];
            foreach ($data['filters'] as $filter) {
                if (($filter['field'] === 'vendor' || $filter['field'] === 'object' || $filter['field'] === 'legal_entity' ||
                        $filter['field'] === 'material' || $filter['field'] === 'material_type')
                    && (!is_int($filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
                }
                if ($filter['field'] === 'vendor' && !Vendors::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$NO_SUPPLIER_WITH_ID_FOUND);
                }
                if ($filter['field'] === 'object' && !Objects::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$NO_OBJECT_WITH_ID_WAS_FOUND);
                }
                if ($filter['field'] === 'legal_entity' && !LegalEntities::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND);
                }
                if ($filter['field'] === 'material' && !Materials::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$MATERIAL_WITH_ID_NOT_FOUND);
                }
                if ($filter['field'] === 'material_type' && !LegalEntitiesTypes::checkIsNotExitsId($filter['value'])) {
                    return General::generalMethod($request, 400, [], $this, Constants::$TYPE_ORGANIZATION_WITH_THIS_ID_WAS_NOT_FOUND);
                }
                if (($filter['field'] === 'price' || $filter['field'] === 'total') && (!is_float((float)$filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$SUM_MUST_INTEGER_AND_MUST_GREATER_ZERO);
                }
                if ($filter['field'] === 'volume' && (!is_float((float)$filter['value']) || $filter['value'] <= 0)) {
                    return General::generalMethod($request, 400, [], $this, Constants::$VOLUME_MUST_INTEGER_AND_MUST_GREATER_ZERO);
                }
                if (($filter['field'] === 'price' || $filter['field'] === 'total') && (!($filter['value'] < 1000000000))) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$AMOUNT_MUST_LESS_THAN_1000000000RUBLES);
                }
                if ($filter['field'] === 'volume' && !($filter['value'] < 1000000000)) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$VOLUME_MUST_LESS_THAN_1000000000);
                }
                if ($filter['field'] === 'confirm_history_operation' && $filter['value'] !== 0 &&
                    $filter['value'] !== 1) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$CONFIRMATED_DATA_IS_ACTIVE_OR_NO_ACTIVE);
                }
                if (($filter['field'] === 'date_from' || $filter['field'] === 'date_to') && (!is_int($filter['value']) || !is_int($filter['value']))) {
                    return General::generalMethod($request, 400, $filter, $this, Constants::$DATE_MUST_BE_INTEGER);
                }
                if ($filter['field'] === 'date_from' && $filter['value'] === 0) {
                    $filter['date_from'] = $date->getTimestamp();
                }
                if ($filter['field'] === 'date_to' && $filter['value'] === 0) {
                    $filter['date_to'] = $date->getTimestamp();
                }
                $fields[] = $filter['field'];
            }
            if (in_array('material', $fields) && in_array('material_type', $fields)) {
                return General::generalMethod($request, 400, [], $this, Constants::$FILTER_NOT_INCLUDES_MATERIAL_AND_MATERIAL_TYPES_IN_PARALLEL);
            }
            $result = Filters::updateFilters($data);
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 200, $data, $this, Constants::$FILTER_SUCCESSFULLY_UPDATED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $value
     * @param array $file
     * @param array $history_operations
     * @return array
     */
    public function getOperations($value, array $file, array $history_operations): array
    {
        $history_operations[] = [
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
            'volume' => (float)$value['volume'],
            'created_at' => $value['created_at'],
            'confirmed_data' => $value['confirmed_data'],
            'price' => (float)$value['price'],
            'total' => (float)$value['total'],
            'file' => $file,
            'comment' => $value['comment'],
            'legal_entity' => [
                "id" => $value->legalEntity['id'],
                "legal_entities_type_id" => $value->legalEntity['legal_entities_type_id'],
                "legal_entities_type" => $value->legalEntity->type['name'],
                "name" => $value->legalEntity['name'],
            ],
        ];
        return $history_operations;
    }
}