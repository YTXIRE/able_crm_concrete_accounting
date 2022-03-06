<?php

namespace app\controllers\api;

use app\models\Files;
use app\models\Rights;
use app\models\Users;
use app\models\UserTimeZone;
use Constants;
use general\General;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\web\Response;

class UsersController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Info(title="API документация", version="0.0.3")
     */
    /**
     * @OA\Get(
     *     path="/api/users/get-all",
     *     summary="Получить информацию обо всех пользователях",
     *     operationId="get-all",
     *     tags={"users"},
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
     *                          "users": {
     *                              {
     *                                  "id": 1,
     *                                  "login": "user",
     *                                  "email": "test@it-paradise.com",
     *                                  "created_at": 1635348594,
     *                                  "last_login_at": 1635888274,
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
            $users = [
                'users' => Users::getAllUsers($limit, $offset),
                'count' => (int)Users::getAllCount()
            ];
            return General::success($users, $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/get-info",
     *     summary="Получить информацию о конкретном пользователе",
     *     operationId="get-info",
     *     tags={"users"},
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
     *                          "id": 1,
     *                          "login": "user",
     *                          "timezone": "Europe/Moscow",
     *                          "email": "test@it-paradise.ru",
     *                          "created_at": 1635348594,
     *                          "last_login_at": 1635970339,
     *                          "avatar": "/web/files/yW2onkqZvYqRU71mJ3EehRIOFJuZXN1bYIc1UPYQWaQYXh9llwgDcwRFuIOMmNWg.png",
     *                          "rights": {
     *                              "is_admin": true
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
    function actionGetInfo($token = '', $user_id = 0): Response
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
            $result = [];
            foreach (Users::getUserInfo($token) as $key => $value) {
                if (in_array($key, ['password', 'token'])) continue;
                $result[$key] = $value;
            }
            $result['timezone'] = UserTimeZone::getUserTimezone($result['id']);
            $result['rights']['is_admin'] = Rights::isAdmin($result['id']);
            $avatar = Files::getUserFile($result['id'], 'avatar');
            $result['avatar'] = $avatar ? "/web/files/" . $avatar['filename'] : null;
            return General::success($result, $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/users/create",
     *     summary="Создание нового пользователя",
     *     operationId="create",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="login",
     *                     description="Логин пользователя",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Адрес электронной почты пользователя",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Пароль пользователя",
     *                     type="string"
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
     *                     "data": {
     *                          "id": 1,
     *                          "login": "user",
     *                          "email": "test@it-paradise.com",
     *                          "created_at": 1635348594
     *                      }
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Не верные данные",
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
     *                 example="Пожалуйста, убедитесь, что все необходимые поля заполнены правильно",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, убедитесь, что все необходимые поля заполнены правильно"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина логина может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина логина может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина электронной почты может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина электронной почты может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина пароля может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина пароля может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Недостаточно прав. Обратитесь к своему администратору",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Недостаточно прав. Обратитесь к своему администратору"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Неверный адрес электронной почты. Пожалуйста, проверьте правильность адреса электронной почты",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Неверный адрес электронной почты. Пожалуйста, проверьте правильность адреса электронной почты"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Пароль должен содержать не менее 5 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пароль должен содержать не менее 5 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Указанным адрес электронной почты или логин уже занят",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Указанным адрес электронной почты или логин уже занят"
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
    /**
     * @throws \yii\base\Exception
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
            $data = $request->post();
            $data = [
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'login' => array_key_exists('login', $data) ? trim($data['login']) : '',
                'email' => array_key_exists('email', $data) ? trim($data['email']) : '',
                'password' => array_key_exists('password', $data) ? trim($data['password']) : '',
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
            ];
            if (!array_key_exists('login', $data) || !array_key_exists('email', $data) ||
                !array_key_exists('password', $data) || !array_key_exists('token', $data)
                || empty($data['login']) || empty($data['email']) || empty($data['password']) || empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_MAKE_SURE_THAT_ALL_THE_REQUIRED_FIELDS_ARE_FILLED_IN_CORRECTLY);
            }
            if (!is_int($data['user_id']) || $data['user_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (mb_strlen($data['login']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LOGIN_LENGTH);
            }
            if (mb_strlen($data['email']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_EMAIL_100_CHARACTERS);
            }
            if (mb_strlen($data['password']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_PASSWORD_LENGTH);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            if (!Rights::getIsAdminWithToken($data['token'])) {
                return General::generalMethod($request, 400, [], $this, Constants::$NOT_ENOUGH_RIGHTS_CONTACT_YOUR_ADMINISTRATOR);
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$INVALID_EMAIL_ADDRESS);
            }
            if (mb_strlen($data['password']) < 5) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PASSWORD_MUST_CONTAIN_LEAST_5_CHARACTERS);
            }
            if (Users::checkUserData($data)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$EMAIL_ADDRESS_OR_LOGIN_ALREADY_OCCUPIED);
            }
            $result = Users::createUser($data);
            if ($result['code'] == 0) {
                throw new Exception('Ошибка в базе данных');
            }
            UserTimeZone::saveTimeZone(1, $result['id']);
            Yii::$app->response->statusCode = 201;
            return General::success([
                'id' => $result['id'],
                'login' => $result['login'],
                'email' => $result['email'],
                'created_at' => $result['created_at'],
            ], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/update-info",
     *     summary="Обновление информации о конкретном пользователе",
     *     operationId="update-info",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID пользователя",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="login",
     *                     description="Логин пользователя",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Электронная почта пользователя",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Пароль пользователя",
     *                     type="string"
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
     *                     "data": {
     *                          "id": 1,
     *                          "login": "user",
     *                          "email": "test@it-paradise.ru"
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
     *                 example="Максимальная длина токена может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина токена может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина логина может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина логина может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Максимальная длина электронной почты может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина электронной почты может быть 100 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Неверный адрес электронной почты. Пожалуйста, проверьте правильность адреса электронной почты",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Неверный адрес электронной почты. Пожалуйста, проверьте правильность адреса электронной почты"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Пароль должен содержать не менее 5 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пароль должен содержать не менее 5 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Указанным адрес электронной почты или логин уже занят",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Указанным адрес электронной почты или логин уже занят"
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
     *                 example="Пользователь с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Пользователь с указанным идентификатором не найден"
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
    function actionUpdateInfo(): Response
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
                'login' => array_key_exists('login', $data) ? trim($data['login']) : '',
                'email' => array_key_exists('email', $data) ? trim($data['email']) : '',
                'password' => array_key_exists('password', $data) ? trim($data['password']) : '',
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
            ];
            if (!array_key_exists('login', $data) || !array_key_exists('email', $data)
                || !array_key_exists('token', $data) || empty($data['login']) || empty($data['email'])
                || empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_MAKE_SURE_THAT_ALL_THE_REQUIRED_FIELDS_ARE_FILLED_IN_CORRECTLY);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (mb_strlen($data['login']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LOGIN_LENGTH);
            }
            if (mb_strlen($data['email']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LENGTH_EMAIL_100_CHARACTERS);
            }
            if (mb_strlen($data['password']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_PASSWORD_LENGTH);
            }
            if (!is_int($data['id']) || $data['id'] <= 0 || !is_int($data['user_id']) || $data['user_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkExistUserWithId($data['id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_ID_NOT_FOUND);
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$INVALID_EMAIL_ADDRESS);
            }
            if (!empty($data['password']) && mb_strlen($data['password']) < 5) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PASSWORD_MUST_CONTAIN_LEAST_5_CHARACTERS);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            if (Users::checkUserData($data)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$EMAIL_ADDRESS_OR_LOGIN_ALREADY_OCCUPIED);
            }
            if (!empty($data['password'])) {
                Users::changeUserPassword($data);
            }
            $data = Users::updateUserInfo($data);
            if ($data['code'] == 0) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::success([
                'id' => $data['id'],
                'login' => $data['login'],
                'email' => $data['email']
            ], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/users/change-password",
     *     summary="Обновление пароля ",
     *     operationId="change-password",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID пользователя",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Пароль пользователя",
     *                     type="string"
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
     *                     "message": "Пароль был успешно изменен"
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
     *                 example="Максимальная длина пароля может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина пароля может быть 100 символов"
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
     *                 example="Пожалуйста, убедитесь, что все необходимые поля заполнены правильно",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пожалуйста, убедитесь, что все необходимые поля заполнены правильно"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Пароль должен содержать не менее 5 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Пароль должен содержать не менее 5 символов"
     *                  }
     *              ),
     *              @OA\Examples(
     *                 example="Вы не можете изменить пароль администратора",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Вы не можете изменить пароль администратора"
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
     *                 example="Пользователь с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Пользователь с указанным идентификатором не найден"
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
    function actionChangePassword(): Response
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
                'password' => array_key_exists('password', $data) ? trim($data['password']) : '',
                'user_id' => array_key_exists('user_id', $data) ? (int)($data['user_id']) : 0,
            ];
            if (!array_key_exists('password', $data) || !array_key_exists('token', $data) ||
                empty($data['password']) || empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_MAKE_SURE_THAT_ALL_THE_REQUIRED_FIELDS_ARE_FILLED_IN_CORRECTLY);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (mb_strlen($data['password']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_PASSWORD_LENGTH);
            }
            if (!is_int($data['id']) || $data['id'] <= 0 || !is_int($data['user_id']) || $data['user_id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (mb_strlen($data['password']) < 5) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PASSWORD_MUST_CONTAIN_LEAST_5_CHARACTERS);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkExistUserWithId($data['id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_ID_NOT_FOUND);
            }
            if (Rights::isAdmin($data['id'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$YOU_CANNOT_CHANGE_PASSWORD_ADMINISTRATOR);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $data['user_id'], 'token' => $data['token']])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $result = Users::changeUserPassword($data);
            if ($result == 0) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::generalMethod($request, 200, $data, $this, Constants::$PASSWORD_SUCCESSFULLY_CHANGED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/delete",
     *     summary="Удаление пользователя",
     *     operationId="delete",
     *     tags={"users"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID пользователя",
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
     *                     "message": "Пользователь был успешно удален"
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
     *                 example="Вы не можете удалить администратора",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Вы не можете удалить администратора"
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
     *                 example="Пользователь с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Пользователь с указанным идентификатором не найден"
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
            $user_id = array_key_exists('user_id', $data) ? (int)($data['user_id']) : '';
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
            if (!Users::checkExistUserWithId($id)) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_ID_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            if (Rights::isAdmin($id)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$YOU_CANNOT_REMOVE_ADMINISTRATOR);
            }
            Users::deleteUser($id);
            UserTimeZone::deleteTimeZone($id);
            return General::generalMethod($request, 200, $data, $this, Constants::$USER_SUCCESSFULLY_DELETED);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}