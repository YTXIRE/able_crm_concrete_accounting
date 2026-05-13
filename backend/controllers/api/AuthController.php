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
        $request = Yii::$app->request;

        try {
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }

            if (!$request->isPost) {
                return General::generalMethod($request, 405, [], $this, Constants::$POST_METHOD_NOT_ALLOWED);
            }

            $post = $request->post();

            $login = isset($post['login']) ? trim($post['login']) : '';
            $passwordInput = isset($post['password']) ? trim($post['password']) : '';

            if ($login === '' || $passwordInput === '') {
                return General::generalMethod(
                    $request,
                    400,
                    [],
                    $this,
                    Constants::$PLEASE_MAKE_SURE_THAT_ALL_THE_REQUIRED_FIELDS_ARE_FILLED_IN_CORRECTLY
                );
            }

            if (mb_strlen($login) > 100) {
                return General::generalMethod($request, 400, [], $this, Constants::$MAXIMUM_LOGIN_LENGTH);
            }

            if (mb_strlen($passwordInput) > 100) {
                return General::generalMethod($request, 400, [], $this, Constants::$MAXIMUM_PASSWORD_LENGTH);
            }

            $user = Users::checkExistUserWithLogin($login);

            if (!$user || !isset($user['password'])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_NOT_FOUND);
            }

            if (!Yii::$app->security->validatePassword($passwordInput, $user['password'])) {
                return General::generalMethod($request, 400, [], $this, Constants::$INCORRECT_LOGIN_OR_PASSWORD);
            }

            $token = Users::checkTokenUserWithLogin($login);

            if ($token && !empty($token['token'])) {
                return General::success([
                    'token' => $token['token'],
                    'id' => $token['id'],
                    'is_demo' => $token['is_demo']
                ], $request, $this);
            }

            $result = Users::generateToken(['login' => $login]);

            if (!$result || empty($result['token'])) {
                throw new \Exception('Ошибка базы данных');
            }

            return General::success([
                'token' => $result['token'],
                'id' => $result['id'],
                'is_demo' => $result['is_demo']
            ], $request, $this);

        } catch (\Throwable $e) {
            return General::generalMethod(
                $request,
                500,
                [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine()
                ],
                $this,
                Constants::$INTERNAL_SERVER_ERROR
            );
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
        $request = Yii::$app->request;

        try {
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }
            if (!$request->isPost) {
                return General::generalMethod($request, 405, [], $this, Constants::$POST_METHOD_NOT_ALLOWED);
            }
            $data = $request->post();
            $token = array_key_exists('token', $data) ? trim($data['token']) : '';
            if ($token === '') {
                $authHeader = $request->headers->get('Authorization');
                if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                    $token = trim($matches[1]);
                }
            }
            if ($token === '') {
                return General::generalMethod($request, 400, [], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            $user_id = array_key_exists('user_id', $data) ? (int)$data['user_id'] : 0;
            if ($user_id <= 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (mb_strlen($token) > 100) {
                return General::generalMethod(
                    $request,
                    400,
                    [],
                    $this,
                    Constants::$MAXIMUM_TOKEN_LENGTH
                );
            }
            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID(['id' => $user_id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            $result = Users::removeToken($token);
            if ($result && isset($result['code']) && $result['code'] == 0) {
                throw new \Exception('DB error');
            }
            return General::generalMethod(
                $request,
                200,
                [],
                $this,
                Constants::$USER_SUCCESSFULLY_LOGOUT
            );
        } catch (\Throwable $e) {
            Yii::error([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 'logout');
            return General::generalMethod(
                $request,
                500,
                [],
                $this,
                Constants::$INTERNAL_SERVER_ERROR
            );
        }
    }
}
