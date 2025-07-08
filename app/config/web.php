<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'JfzjwgqQn85xuX7PkpE8KlF7hsuJ1OYyIAiq76pzYpI=',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/app.log',
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST api/login' => 'api/login',
                'GET api/medicines' => 'api/medicine-index',
                'POST api/medicines' => 'api/medicine-create',
                'GET api/reminders' => 'api/reminder-index',
                'POST api/reminders' => 'api/reminder-create',
                'POST api/reminders/<id:\d+>/take' => 'api/reminder-take',
                'DELETE api/reminders/<id:\d+>' => 'api/reminder-delete',
            ],
        ],
    ],
    'params' => $params,
];

return $config;