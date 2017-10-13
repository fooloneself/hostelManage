<?php
$basePath=dirname(dirname(__DIR__));
$config= [
    'id'=>'openPlatform',
    'basePath'=>        $basePath,
    'vendorPath' =>     $basePath .DIRECTORY_SEPARATOR. 'vendor',
    'viewPath'=>        $basePath .DIRECTORY_SEPARATOR. 'view',
    'defaultRoute'=>    '/error/error',
    'aliases'=>[
        'common'    =>  $basePath.'/common',
        'modules'   =>  $basePath.'/modules',
        'service'   =>  $basePath.'/service',
        'resource'  =>  $basePath.DIRECTORY_SEPARATOR.'resource'
    ],
    'controllerNamespace'=>'app\controllers',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => require('db.php'),
        'user'=>[
            'class'=>'common\components\WebUser',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
        ],
        'request'=>[
            'enableCsrfValidation'=>false
        ],
        'log'=>[
            'class'=>'common\components\log\Log',
            'targets'=>[
                'icRecord'=>['logFile'=>'@app/log/icRecord'],
                'visit'=>[
                    'class'=>'common\components\log\DbTarget',
                    'modelClass'=>'common\models\PartnerRequestLog'
                ]
            ]
        ],
        'requestHelper'=>'common\components\RequestHelper',
        'responseHelper'=>'common\components\ResponseHelper',
        'errorManager'=>'common\components\ErrorManager',
       /* 'errorHandler' => [
            'class'=>'app\common\components\ErrorHandler'
        ],*/
    ],
    'modules'=>[
        'platform'=>'modules\platform\Module',
        'merchant'=>'modules\merchant\Module'
    ],
    'params'=>[
        'expire_time'=>172800, //2天
    ]
];
if(YII_ENV_DEV){
    $config['modules']['gii']='yii\gii\Module';
}
return $config;