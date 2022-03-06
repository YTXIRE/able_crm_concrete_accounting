<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use function OpenApi\scan;

class DefaultController extends Controller
{
    public function actionSwagger(): int
    {
        $openApi = scan(Yii::getAlias('@app/controllers/'));
        $file = Yii::getAlias('@web/docs/swagger.json');

        $handle = fopen($file, 'wb');
        fwrite($handle, $openApi->toJson());
        fclose($handle);

        echo $this->ansiFormat('Created');

        return ExitCode::OK;
    }
}
// Use php yii default/swagger
// Use php yii migrate/create name
// Use php yii gii/model --tableName filters --modelClass Filters
