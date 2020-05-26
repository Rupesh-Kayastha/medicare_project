<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-company',
	'name'=> 'Arunodaya Medicare',
	'homeUrl' => Yii::getAlias('@companyUrl'),
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'company\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
	'components' => [
        'request' => [
            'csrfParam' => '_csrf-company',
			'baseUrl' => '/company',
        ],
        'user' => [
            'identityClass' => 'common\models\Employee',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-company', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the company
            'name' => 'advanced-company',
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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
		'i18n' => [
			'translations' => [
				'*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@company/messages', // if advanced application, set @frontend/messages
					'sourceLanguage' => 'en',
					'fileMap' => [
						//'main' => 'main.php',
					],
				],
			],
		],
       
    ],
    'params' => $params,
];
