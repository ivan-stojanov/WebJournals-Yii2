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
		/*'request'=>[
            'baseUrl'=>'',
        ],
        'urlManager'=>[
            'scriptUrl'=>'/index.php',
		],*/
        'user' => [
            'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			/*'identityCookie' => [
				'name' => '_identity-frontend',
				'httpOnly' => true,
				'domain' => '.evidencija',
			],*/
        ],
    	'pdf' => [
    		'class' => \kartik\mpdf\Pdf::classname(),
    		// set to use core fonts only
    		//'mode' => \kartik\mpdf\Pdf::MODE_CORE,
    		// A4 paper format
    		'format' => \kartik\mpdf\Pdf::FORMAT_A4,
    		// portrait orientation
    		'orientation' => \kartik\mpdf\Pdf::ORIENT_PORTRAIT,
    		// stream to browser inline
    		'destination' => \kartik\mpdf\Pdf::DEST_BROWSER,
    		// format content from your own css file if needed or use the
    		// enhanced bootstrap css built by Krajee for mPDF formatting
    		'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
    		// any css to be embedded if required
    		'cssInline' => '.kv-heading-1{font-size:18px}',
    		// refer settings section for all configuration options
    	],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_volumes'],
            		'logFile' => '@frontend/runtime/logs/custom_errors_volumes.log',
            	],
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_issues'],
            		'logFile' => '@frontend/runtime/logs/custom_errors_issues.log',
            	],
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_sections'],
            		'logFile' => '@frontend/runtime/logs/custom_errors_sections.log',
            	],
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_articles'],
            		'logFile' => '@frontend/runtime/logs/custom_errors_articles.log',
            	],
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_users'],
            		'logFile' => '@frontend/runtime/logs/custom_errors_users.log',
            	],            		
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
