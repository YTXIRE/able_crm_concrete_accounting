<?php

namespace app\controllers\api;

use app\models\Files;
use app\models\Users;
use Constants;
use general\General;
use Yii;
use yii\db\Exception;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class FilesController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @OA\Post(
     *     path="/api/files/upload-avatar",
     *     summary="Загрузка аватара",
     *     operationId="upload-avatar",
     *     tags={"files"},
     *     @OA\RequestBody(
     *         description="Формат входных данных",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     description="ID пользователя",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     description="Картинка аватара",
     *                     type="file"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     description="Токен пользователя",
     *                     type="string"
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
     *                          "avatar": "/web/files/yW2onkqZvYqRU71mJ3EehRIOFJuZXN1bYIc1UPYQWaQYXh9llwgDcwRFuIOMmNWg.png"
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
     *             ),
     *             @OA\Examples(
     *                 example="Максимальная длина токена может быть 100 символов",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Максимальная длина токена может быть 100 символов"
     *                  }
     *             ),
     *             @OA\Examples(
     *                 example="Идентификатор должен быть целым числом и должен быть больше нуля",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Идентификатор должен быть целым числом и должен быть больше нуля"
     *                  }
     *             ),
     *             @OA\Examples(
     *                 example="Укажите файл",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Укажите файл"
     *                  }
     *             ),
     *             @OA\Examples(
     *                 example="Файл имеет не допустимое расширение. Должно быть: png, jpeg или jpg",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Файл имеет не допустимое расширение. Должно быть: png, jpeg или jpg"
     *                  }
     *             ),
     *             @OA\Examples(
     *                 example="Размер файла должен быть меньше 500 пикселей по ширине и высоте",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Размер файла должен быть меньше 500 пикселей по ширине и высоте"
     *                  }
     *             ),
     *             @OA\Examples(
     *                 example="Вес файла должен быть менее 500 килобайт",
     *                 summary="",
     *                 value={
     *                     "code": 400,
     *                     "status": "Bad Request",
     *                     "message": "Вес файла должен быть менее 500 килобайт"
     *                  }
     *             )
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
     *                     "message": "ТПользователь с указанным токеном не найден"
     *                  }
     *              ),
     *             @OA\Examples(
     *                 example="Пользователь с указанным идентификатором не найден",
     *                 summary="",
     *                 value={
     *                     "code": 404,
     *                     "status": "Not Found",
     *                     "message": "Пользователь с указанным идентификатором не найден"
     *                  }
     *              ),
     *             @OA\Examples(
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
    function actionUploadAvatar(): Response
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
            $uploads = UploadedFile::getInstancesByName("avatar");
            $data = [
                'id' => array_key_exists('id', $data) ? (int)trim($data['id']) : 0,
                'token' => array_key_exists('token', $data) ? trim($data['token']) : '',
                'avatar' => $uploads,
            ];
            if (empty($data['token'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }
            if (mb_strlen($data['token']) > 100) {
                return General::generalMethod($request, 400, $data, $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }
            if (!Users::checkExistUserWithToken($data['token'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }
            if (!is_int($data['id']) || $data['id'] <= 0) {
                return General::generalMethod($request, 400, $data, $this, Constants::$ID_MUST_BE_INTEGER);
            }
            if (!Users::checkExistUserWithId($data['id'])) {
                return General::generalMethod($request, 404, $data, $this, Constants::$USER_WITH_ID_NOT_FOUND);
            }
            if (!Users::checkUserWithTokenAndID($data)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }
            if (empty($data['avatar'])) {
                return General::generalMethod($request, 400, $data, $this, Constants::$SPECIFY_FILE);
            }
            $ext_type = $data['avatar'][0]->type;
            $ext = explode('/', $data['avatar'][0]->type)[1];
            $extensions = ['image/png', 'image/jpg', 'image/jpeg'];
            if (!$ext || in_array("image/$ext", $extensions)) {
                $ext = explode('.', $data['avatar'][0]->name)[1];
            }
            if ($ext_type === '' && in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $ext_type = "image/$ext";
            }
            if (!in_array($ext_type, $extensions)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$FILE_UNRESOLVED_EXTENSION_IMAGE);
            }
            $meta_data = getimagesize($data['avatar'][0]->tempName);
            if (!($meta_data[0] <= 500 && $meta_data[1] <= 500)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$FILE_SIZE_MUST_LESS_THAN_500_PIXELS);
            }
            $file_size = (int)filesize($data['avatar'][0]->tempName) / 1024;
            if (!($file_size <= 500)) {
                return General::generalMethod($request, 400, $data, $this, Constants::$FILE_WEIGHT_MUST_BE_LESS_THAN_500_KILOBYTES);
            }
            $file_in_db = Files::getUserFile($data['id'], 'avatar');
            $filename = Yii::$app->security->generateRandomString(64);
            foreach ($uploads as $file) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/web/files/$filename.$ext";
                $file->saveAs($path);
            }
            if ($file_in_db === null) {
                $result = Files::saveFile("$filename.$ext", $data['id'], 'avatar');
            } else {
                $result = Files::updateFile("$filename.$ext", $data['id'], 'avatar');
                $file_path = $_SERVER['DOCUMENT_ROOT'] . "/web/files/" . $file_in_db['filename'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }

            }
            if ($result === false) {
                throw new Exception('Ошибка в базе данных');
            }
            return General::success(['avatar' => "/web/files/$filename.$ext"], $request, $this);
        } catch (Exception $e) {
            return General::generalMethod($request, 500, $e, $this, Constants::$INTERNAL_SERVER_ERROR);
        }
    }
}