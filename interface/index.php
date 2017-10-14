<?php
//允许跨域
header('Access-Control-Allow-Credentials:true');
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:http://localhost:8080');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
if($_SERVER['REQUEST_METHOD']=='OPTIONS'){
    die();
}
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/common/config/main.php');

(new yii\web\Application($config))->run();
