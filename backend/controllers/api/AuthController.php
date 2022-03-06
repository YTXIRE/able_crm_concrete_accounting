<?php

namespace app\controllers\api;

use app\models\Users;
use Constants;
use general\General;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\web\Response;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Авторизация в системе",
     *     operationId="login",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="login",
     *                     description="Логин пользователя",
     *                     type="string",
     *                     example="user"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Пароль пользователя",
     *                     type="string",
     *                     example="123456"
     *                 )
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
     *                          "token": "$2y$13$xPlidu6umJbJ4V9HWYy3BuBITe/sHNZcyz8BiI407dfaZNDtUVK2m"
     *                      }
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Не верные данные",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
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
     *                 example="Максимальная длина пароля может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина пароля может быть 100 символов"
     *                 }
     *             ),
     *             @OA\Examples(
     *                 example="Неверный логин или пароль",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Неверный логин или пароль"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Данные не найдены",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="Такого пользователя не существует",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Такого пользователя не существует"
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
     *      )
     * )
     */
    function actionLogin(): Response
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
                'login' => array_key_exists('login', $data) ? trim($data['login']) : '',
                'password' => array_key_exists('password', $data) ? trim($data['password']) : '',
            ];
            if (!array_key_exists('login', $data) || !array_key_exists('password', $data) ||
                empty($data['login']) || empty($data['password'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_MAKE_SURE_THAT_ALL_THE_REQUIRED_FIELDS_ARE_FILLED_IN_CORRECTLY);
            }
            if (mb_strlen($data['login']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_LOGIN_LENGTH);
            }
            if (mb_strlen($data['password']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_PASSWORD_LENGTH);
            }
            $password = Users::checkExistUserWithLogin($data['login']);
            if (!$password) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_NOT_FOUND);
            }
            if (!Yii::$app->getSecurity()->validatePassword($data['password'], $password['password'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$INCORRECT_LOGIN_OR_PASSWORD);
            }
            $token = Users::checkTokenUserWithLogin($data['login']);
            if ($token['token']) {
                return General::success(['token' => $token['token'], 'id' => $token['id']], $request, $this);
            }
            $result = Users::generateToken($data);
            if ($result['code'] == 0) {
                throw new Exception('Ошибка базы данных');
            }
            return General::success(['token' => $result['token'], 'id' => $result['id']], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Выход из системы",
     *     operationId="logout",
     *     tags={"auth"},
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
     *                     property="user_id",
     *                     description="ID пользователя",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="Пользователь успешно вышел из системы",
     *                 summary="",
     *                 value={
     *                     "code": 200,
     *                     "status": "OK",
     *                     "message": "Пользователь успешно вышел из системы"
     *                  }
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Не верные данные",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Examples(
     *                 example="Пожалуйста, укажите токен пользователя",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Not Found",
     *                     "message": "Пожалуйста, укажите токен пользователя"
     *                  }
     *              ),
     *             @OA\Examples(
     *                 example="Максимальная длина токена может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Not Found",
     *                     "message": "Максимальная длина токена может быть 100 символов"
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
     *     ),
     * )
     */
    function actionLogout(): Response
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
            $token = array_key_exists('token', $data) ? trim($data['token']) : '';
            $user_id = array_key_exists('user_id', $data) ? trim($data['user_id']) : '';
            if (empty($token)) {
                return General::generalMethod($request, 400, $token, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, $token, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $result = Users::removeToken($token);
            if ($result['code'] == 0) {
                throw new Exception('Ошибка базы данных');
            }
            return General::generalMethod($request, 200, $data, $this, Constants::$USER_SUCCESSFULLY_LOGOUT);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}