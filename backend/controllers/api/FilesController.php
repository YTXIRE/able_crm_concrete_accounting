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
     *                          "avatar": "/files/yW2onkqZvYqRU71mJ3EehRIOFJuZXN1bYIc1UPYQWaQYXh9llwgDcwRFuIOMmNWg.png"
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
        $request = Yii::$app->request;

        try {
            if ($request->isOptions) {
                return General::generalMethod($request, 200, [], $this, Constants::$OK);
            }

            if (!$request->isPost) {
                return General::generalMethod($request, 405, [], $this, Constants::$POST_METHOD_NOT_ALLOWED);
            }

            $post = $request->post();

            $id = isset($post['id']) ? (int)$post['id'] : 0;
            $token = isset($post['token']) ? trim($post['token']) : '';

            if (!$token) {
                return General::generalMethod($request, 400, [], $this, Constants::$PLEASE_SPECIFY_USER_TOKEN);
            }

            if (mb_strlen($token) > 100) {
                return General::generalMethod($request, 400, [], $this, Constants::$MAXIMUM_TOKEN_LENGTH);
            }

            if ($id <= 0) {
                return General::generalMethod($request, 400, [], $this, Constants::$ID_MUST_BE_INTEGER);
            }

            if (!Users::checkExistUserWithToken($token)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_NOT_FOUND);
            }

            if (!Users::checkExistUserWithId($id)) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_ID_NOT_FOUND);
            }

            if (!Users::checkUserWithTokenAndID(['id' => $id, 'token' => $token])) {
                return General::generalMethod($request, 404, [], $this, Constants::$USER_WITH_TOKEN_AND_ID_NOT_FOUND);
            }

            $file = UploadedFile::getInstanceByName('avatar');

            if (!$file) {
                return General::generalMethod($request, 400, [], $this, Constants::$SPECIFY_FILE);
            }

            if (!$file->tempName || !file_exists($file->tempName)) {
                return General::generalMethod($request, 400, [], $this, 'Invalid upload');
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file->tempName);

            $allowed = ['image/png', 'image/jpeg', 'image/jpg'];

            if (!in_array($mime, $allowed)) {
                return General::generalMethod($request, 400, [], $this, Constants::$FILE_UNRESOLVED_EXTENSION_IMAGE);
            }

            $meta = getimagesize($file->tempName);

            if ($meta === false) {
                return General::generalMethod($request, 400, [], $this, 'Invalid image');
            }

            if ($meta[0] > 500 || $meta[1] > 500) {
                return General::generalMethod($request, 400, [], $this, Constants::$FILE_SIZE_MUST_LESS_THAN_500_PIXELS);
            }

            $sizeKb = filesize($file->tempName) / 1024;

            if ($sizeKb > 500) {
                return General::generalMethod($request, 400, [], $this, Constants::$FILE_WEIGHT_MUST_BE_LESS_THAN_500_KILOBYTES);
            }

            $ext = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
            if (!$ext) {
                $ext = explode('/', $mime)[1];
            }

            $filename = Yii::$app->security->generateRandomString(64);

            $dir = Yii::getAlias('@webroot/files/');
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $path = $dir . $filename . '.' . $ext;

            if (!$file->saveAs($path)) {
                throw new \Exception('Save failed');
            }

            $fileInDb = Files::getUserFile($id, 'avatar');
            if ($fileInDb) {
                Files::updateFile("$filename.$ext", $id, 'avatar');
                $oldPath = $dir . $fileInDb['filename'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            } else {
                Files::saveFile("$filename.$ext", $id, 'avatar');
            }

            return General::success(
                ['avatar' => "/files/$filename.$ext"],
                $request,
                $this
            );

        } catch (\Throwable $e) {
            return General::generalMethod(
                $request,
                500,
                [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                ],
                $this,
                Constants::$INTERNAL_SERVER_ERROR
            );
        }
    }
}
