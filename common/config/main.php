<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
        'charset' => 'utf-8', //默认编码
        // 'language' => 'zh-CN', //语言
        'timeZone' => 'Asia/Shanghai', //默认时区
    ],
];
