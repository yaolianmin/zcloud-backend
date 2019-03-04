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
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                            'application/json' => 'yii\web\JsonParser',
                            'text/json' => 'yii\web\JsonParser',
                        ],
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
			'enableStrictParsing' => true,
			//'suffix' => '.php', //假后缀
            'rules' => [
                [
                'class' => 'yii\rest\UrlRule',
                'controller' => ['book','dev-info', 'templet-f-a-p','product-management','task-management','device-management','php/Device' => 'device','template','user-management','alert','login','system-log','find-password','group-management','home','common-dev','dev-log'],
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
								   'POST edit_template' => 'edit_template',
								   'POST edit_vaptemplate' => 'edit_vaptemplate',
								   'POST add_template' => 'add_template',
								   'GET get_templatedata' => 'get_templatedata',
                                   'GET search' => 'view-by-search',//前面是请求的URL，后面是具体的动作
                				   'GET managerlist' => 'get-manager-list',
                                   'GET task_edit_view' => 'task-edit-view',
                                   'POST updateTemplate/{id}' => 'update-template',
                                ],
                ]
            ],
        ], 
        //以下的配置是使用邮箱发送消息的配置信息
        'mailer' => [
                'class' => 'yii\swiftmailer\Mailer',
                'viewPath' => '@common/mail',
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    //我用的是QQ 的代理，所以这里是 QQ 的配置信息
                    'host' => 'smtp.qq.com',
                    'port' => 587,
                    'encryption' => 'tls',    
                    //这部分信息不应该公开，所以后期会由数据库中拿取
                    'username' => '1137500763',
                    'password' => 'swrmmuwrffulibhg',//这个密码是由qq开启smtp后系统自动给的
                ],
                //发送的邮件信息配置
                'messageConfig' => [

                    'charset' => 'utf-8',

                    'from' => ['1137500763@qq.com' => '南京智威亚通信科技有限公司']
                ],
        ],  
    ],

    'params' => $params,

    'charset' => 'utf-8', //默认编码
    'language' => 'zh-CN', //语言
    'timeZone' => 'Asia/Shanghai', //默认时区
    
];
