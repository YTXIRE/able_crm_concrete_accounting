<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require(__DIR__ . '/../helpers/Helper.php');
require(__DIR__ . '/../general/General.php');
require(__DIR__ . '/../constants/Constants.php');
require(__DIR__ . '/../vendor/yandex-php-library/vendor/autoload.php');


$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();