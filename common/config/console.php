<?php
$basePath=__DIR__.'/../..';
return [
    'id' => 'yii-console',
    'basePath' => $basePath . '/commands',
    'aliases'=>[
        'app'       =>  $basePath,
        'common'    =>  $basePath.'/common',
        'modules'   =>  $basePath.'/modules',
        'service'   =>  $basePath.'/service',
    ],
    'modules'=>[
        'common'=>'modules\common\Module',
        'electronic'=>'modules\electronic\Module',
    ],
    'controllerMap'=>[
        'interface'=> 'app\commands\controllers\Entrance'
    ],
    'components' => [
        'db' => require('db.php')
    ]
];