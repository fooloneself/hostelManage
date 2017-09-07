<?php
$basePath=dirname(dirname(__DIR__));
$config= [
    'id'=>'openPlatform',
    'basePath'=>        $basePath,
    'vendorPath' =>     $basePath .DIRECTORY_SEPARATOR. 'vendor',
    'viewPath'=>        $basePath .DIRECTORY_SEPARATOR. 'view',
    'defaultRoute'=>    '/common/error/error',
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
        /*'errorHandler' => [
            'class'=>'app\common\components\ErrorHandler'
        ],*/
    ],
    'params'=>[
        'icSocket'=>[
            'IC_API_IP'             =>'192.168.2.127',      //IC卡接口地址
            'IC_API_PORT'           =>9999,                 //IC卡接口端口号
            'MAC_IP'                =>'192.168.20.117',     //      加密机地址
            'MAC_PORT'              =>6666,                 //加密机端口号
            'IC_API_USERNAME'       =>'phoneid',            //IC卡接口帐号
            'IC_API_PWD'            =>'123321',             //IC卡接口密码
            'IC_API_TERMID'         =>'00000450',           //终端编号
            'IC_API_TRBRANCH'       =>'510990310400307',    //交易机构
            'IC_API_TRBRANCH_HXT'   =>'510990120600084',    //和信通交易机构
        ]
    ]
];
if(YII_ENV_DEV){
    $config['modules']['gii']='yii\gii\Module';
}
return $config;