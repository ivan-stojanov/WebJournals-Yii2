<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
            		'logFile' => '@backend/runtime/logs/custom_errors_volumes.log',
            	],   
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_issues'],
            		'logFile' => '@backend/runtime/logs/custom_errors_issues.log',
            	],            		
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_sections'],
            		'logFile' => '@backend/runtime/logs/custom_errors_sections.log',
            	],
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info', 'error', 'warning'],
            		'categories' => ['custom_errors_articles'],
            		'logFile' => '@backend/runtime/logs/custom_errors_articles.log',
            	],            		
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
