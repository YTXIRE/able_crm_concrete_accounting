<?php

namespace app\controllers\api;

use app\models\HistoryOperation;
use app\models\LegalEntities;
use app\models\Payments;
use app\models\Users;
use app\models\Vendors;
use Constants;
use Exception;
use general\General;
use Yii;
use yii\rest\Controller;

class DashboardController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Get(
     *     path="/api/dashboard/get-data",
     *     summary="Получение долгов",
     *     operationId="get-data",
     *     tags={"dashboard"},
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
     *                          "debts": {
     *                              "Поставщик 1": {
     *                                  "Долг": 5000,
     *                                  "Взяли": 2000,
     *                              }
     *                          },
     *                          "operations_by_months": {
     *                              "labels": {
     *                                  "05 Ноября 2021",
     *                                  "04 Ноября 2021",
     *                              },
     *                              "operations": {
     *                                  "Поставщик 1": {
     *                                      0, 100000
     *                                  }
     *                              }
     *                          },
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
    public function actionGetData($token = '', $user_id = 0, $period = 30, $date_from = 0, $date_to = 0)
    {
        try {
            $request = Yii::$app->request;
            if (!$request->isGet) {
                return General::generalMethod($request, 405, [], $this, Constants::$GET_METHOD_NOT_ALLOWED);
            }
            $token = trim($token);
            $user_id = (int)$user_id;
            $date_from = (int)$date_from;
            $date_to = (int)$date_to;
            if ($period === 'all') {
                $period = 99999;
            }
            $period = (int)$period;
            if ($period === 0) {
                $period = 30;
            }
            if (empty($token)) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [$token], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if ($user_id <= 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if ($date_from < 0 || $date_to < 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$DATE_MUST_BE_INTEGER);
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
            $filters_labels = [];
            foreach ($vendors as $vendor) {
                $operations = HistoryOperation::getAllObjectsByVendor($vendor['id']);
                foreach ($operations as $operation) {
                    $date = date('d', $operation['created_at']) . "." .
                        date('m', $operation['created_at']) . "." .
                        date('Y', $operation['created_at']);
                    if (!in_array($date, $labels)) {
                        $labels[] = $date;
                        if ($operation['created_at'] >= $date_from && $operation['created_at'] <= $date_to) {
                            $filters_labels[] = $date;
                        }
                    }
                }
            }
            usort($labels, function ($a, $b) {
                return strtotime($b) - strtotime($a);
            });
            usort($filters_labels, function ($a, $b) {
                return strtotime($b) - strtotime($a);
            });
            $final_labels = [];
            $final_filtered_label = [];
            foreach ($labels as $label) {
                $final_labels[$label] = 0;
                if (in_array($label, $filters_labels)) {
                    $final_filtered_label[$label] = 0;
                }
            }
            if (count($final_filtered_label) === 0) {
                $final_filtered_label = $final_labels;
            }
            foreach ($vendors as $vendor) {
                $operations = HistoryOperation::getAllObjectsByVendor($vendor['id']);
                foreach ($operations as $operation) {
                    if (!array_key_exists($operation->vendor['name'], $history_operations) && $operation->object['is_archive'] === 0) {
                        $history_operations[$operation->vendor['name']] = $final_filtered_label;
                    }
                    $date = date('d', $operation['created_at']) . "." .
                        date('m', $operation['created_at']) . "." .
                        date('Y', $operation['created_at']);
                    if (array_key_exists($date, $final_labels) && $operation->object['is_archive'] === 0) {
                        if (array_key_exists($operation['vendor_id'], $tmp_operations) && array_key_exists($operation['legal_entity_id'], $tmp_operations[$operation['vendor_id']])) {
                            $tmp_operations[$operation['vendor_id']][$operation['legal_entity_id']] += (float)$operation['total'];
                        } else {
                            $tmp_operations[$operation['vendor_id']][$operation['legal_entity_id']] = (float)$operation['total'];
                        }
                    }
                    if (array_key_exists($date, $final_filtered_label) && $operation->object['is_archive'] === 0) {
                        $history_operations[$operation->vendor['name']][$date] += $operation['total'];
                    }
                }
            }
            $result = [];
            $debts = [];
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
                    $debts[$vendor['name']] = [
                        'Взяли' => $take,
                        'Долг' => $debt
                    ];
                }
            }
            $reformat_operations = [
                'operations' => [],
                'labels' => []
            ];
            foreach ($history_operations as $vendor => $date) {
                foreach ($date as $value) {
                    if (array_key_exists($vendor, $reformat_operations['operations'])) {
                        if (count($reformat_operations['operations'][$vendor]) < $period) {
                            $reformat_operations['operations'][$vendor][] = $value;
                        } else {
                            break;
                        }
                    } else {
                        $reformat_operations['operations'][$vendor] = [$value];
                    }
                }
            }
            $reformat_operations['labels'] = array_keys(array_slice($final_filtered_label, 0, $period));
            $result['debts'] = $debts;
            $result['operations_by_months'] = $reformat_operations;
            return General::success($result ?: [], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}