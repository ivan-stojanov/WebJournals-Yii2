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
    					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
    					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    			]
    	],
    	'urlManagerFrontEnd' => [
    			'class' => 'yii\web\urlManager',
    			'baseUrl' => '/WebSpisanieOOSI/frontend/web',
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			'enableStrictParsing' => false,
    			'rules' => [
    					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
    					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    			]
    	],
    	'urlManagerBackEnd' => [
    			'class' => 'yii\web\urlManager',
    			'baseUrl' => '/WebSpisanieOOSI/backend/web',
    			'enablePrettyUrl' => true,
    			'showScriptName' => false,
    			'enableStrictParsing' => false,
    			'rules' => [
    					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
    					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
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
    			'dsn' => 'mysql:host=localhost;dbname=webspisanie_oosi',
    			'username' => 'root',
    			'password' => '',
    			'charset' => 'utf8',
    	],
    ],		
];
