<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
       'response' => [
            'format' =>  'json',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,//路由路径化
            'showScriptName' => false,//隐藏入口脚本
			//'enableStrictParsing' => true,
			//'suffix' => '.php', //假后缀
            'rules' => [
                [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['book','dev-info', 'templet-f-a-p', 'php/Device' => 'device'],
                'extraPatterns' =>[
								   'POST add_newdev' => 'add_newdev',
								   'POST delete_dev' => 'delete_dev',
								   'POST update_devinfo' => 'update_devinfo',
								   'GET view_devinfo' => 'view_devinfo',
								   'GET users/serach'=>'dev-info/serach',
								   'POST addtempletfap' => 'addtempletfap',
								   'POST deletetempletfap' => 'deletetempletfap',
								   'POST updatetempletfap' => 'updatetempletfap',
								   'GET searchtempletfap' => 'searchtempletfap',
								   'GET device.php' => 'device',
								   'device.php' => 'device',
								],
                ]
            ],
        ],   
    ],

    'params' => $params,

    'charset' => 'utf-8', //默认编码
    'language' => 'zh-CN', //语言
    'timeZone' => 'Asia/Shanghai', //默认时区
];
