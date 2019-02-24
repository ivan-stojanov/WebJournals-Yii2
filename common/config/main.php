<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    	'urlManager' => [
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			'enableStrictParsing' => false,
    			'rules' => [
    					'<controller:\w+(-\w+)*>/<id:\d+>'=>'<controller>/view',
    					'<controller:\w+(-\w+)*>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    					'<controller:\w+(-\w+)*>/<action:\w+>'=>'<controller>/<action>',
    			]
    	],
    	'urlManagerFrontEnd' => [
    			'class' => 'yii\web\urlManager',
    			'baseUrl' => '/evidencija/frontend/web',
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			'enableStrictParsing' => false,
    			'rules' => [
    					'<controller:\w+(-\w+)*>/<id:\d+>'=>'<controller>/view',
    					'<controller:\w+(-\w+)*>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    					'<controller:\w+(-\w+)*>/<action:\w+>'=>'<controller>/<action>',
    			]
    	],
    	'urlManagerBackEnd' => [
    			'class' => 'yii\web\urlManager',
    			'baseUrl' => '/evidencija/backend/web',
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			'enableStrictParsing' => false,
    			'rules' => [
    					'<controller:\w+(-\w+)*>/<id:\d+>'=>'<controller>/view',
    					'<controller:\w+(-\w+)*>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    					'<controller:\w+(-\w+)*>/<action:\w+>'=>'<controller>/<action>',
    			]
    	],
    	'urlManagerCommon' => [
    			'class' => 'yii\web\urlManager',
    			'baseUrl' => '/evidencija/common',
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			'enableStrictParsing' => false,
    			'rules' => [
    					'<controller:\w+(-\w+)*>/<id:\d+>'=>'<controller>/view',
    					'<controller:\w+(-\w+)*>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    					'<controller:\w+(-\w+)*>/<action:\w+>'=>'<controller>/<action>',
    			]
    	],
    	'mailer' => [
    			'class' => 'yii\swiftmailer\Mailer',
    			'viewPath' => '@common/mail',
    			'useFileTransport' => false,//set this property to false to send mails to real email addresses
    			'transport' => [
    					'class' => 'Swift_SmtpTransport',
    					'host' => 'smtp.gmail.com',
    					'username' => 'calledout.mobile@gmail.com',
    					'password' => 'abncyljyehgunyeu',
    					'port' => '587',
    					'encryption' => 'tls',
    			],
    	],
    	'db' => [
    			'class' => 'yii\db\Connection',
    			'dsn' => 'mysql:host=localhost;dbname=evidencija',
    			'username' => 'root',
    			'password' => '',
    			'charset' => 'utf8',
    	],
    	/*'fileStorage'=>[
    			'class' => 'trntv\filekit\Storage',
    			'baseUrl' => '@web/uploads',
    			'filesystem'=> function() {
			        $adapter = new \League\Flysystem\Adapter\Local('@web/uploads');
			        return new League\Flysystem\Filesystem($adapter);
    			}
    	],*/
    ],		
];
