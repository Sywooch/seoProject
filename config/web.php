<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'captcha' => [
            'class' => 'jumper423\Captcha',
            'pathTmp' => '@imagescache/captcha',
            'apiKey' => '42eab4119020dbc729f657',
        ],
        'vk' => [
            'class' => 'jumper423\VK',
            'clientId' => '5129413',
            'clientSecret' => 'XZPCpX2GgjlTb8ShaBC3',
            'delay' => 0.7, // Минимальная задержка между запросами
            'delayExecute' => 120, // Задержка между группами инструкций в очереди
            'limitExecute' => 1, // Количество инструкций на одно выполнении в очереди
            'captcha' => 'captcha', // Компонент по распознованию капчи
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        //    'cookieValidationKey' => 'dsadfsdfasdfasdfsadf',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'rosvuz.inform',
                'password' => 'HjcDep2014',
                'port' => '465',
                'encryption' => 'SSL',
                'plugins' => [
                    [
                        'class' => 'Swift_Plugins_LoggerPlugin',
                        'constructArgs' => [new Swift_Plugins_Loggers_ArrayLogger],
                    ],
                ],
            ],
        ],
        'urlManager' => array(
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => array(
                '' => '/site/index',
                '<action:\w+>' => '/site/<action>',
                '/page/<url:[a-z0-9]+>' => '/site/page',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:[\wd-]+>/<controller:[\wd-]+>/<action:[\wd-]+>/<id:\d+>' => '<module>/<controller>/<action>',
            ),
        ),
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logFile' => '@app/runtime/logs/eauth.log',
                    'categories' => ['nodge\eauth\*'],
                    'logVars' => [],
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'dd-MM-Y',
            'datetimeFormat' => 'dd-MM-Y H:i',
            'timeFormat' => 'H:i:s',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'eauth' => [
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => [
            // uncomment this to use streams in safe_mode
            //'useStreamsFallback' => true,
            ],
            'services' => [ // You can change the providers and their classes.
                'google' => [
                    // register your app here: https://code.google.com/apis/console/
                    // need public domen like .com or .ru
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '42436862752-1ohftpo3ngf7h8ev0uk10a2e2h7kjg03.apps.googleusercontent.com',
                    'clientSecret' => 'm7aNSUyOr-f_4-uSLoN6JSN4',
                    'title' => 'Google',
                ],
                'twitter' => [
                    // register your app here: https://dev.twitter.com/apps/new
                    'class' => 'nodge\eauth\services\TwitterOAuth1Service',
                    'key' => 'u2llo2UV3c7PgWrFyUkD3mRl5',
                    'secret' => 'VHKOrM0NwWQuUIr4yCOgKEjIdIfweRrubqHV7zhXLqlPiKWMnF',
                ],
                'yandex' => [
                    // register your app here: https://oauth.yandex.ru/client/my
                    'class' => 'nodge\eauth\services\YandexOAuth2Service',
                    'clientId' => '9cd0159b6d3441418b2fb11ad9de0e29',
                    'clientSecret' => 'faa1be7cab664b0fa680e94946e043dc',
                    'title' => 'Yandex',
                ],
                'facebook' => [
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '125900001103190',
                    'clientSecret' => 'e892d10dc15a1f7ced23ea310f82cfee',
                ],
                'linkedin' => [
                    // register your app here: https://www.linkedin.com/secure/developer
                    'class' => 'nodge\eauth\services\LinkedinOAuth2Service',
                    'clientId' => '77rvrda9wct8r5',
                    'clientSecret' => 'FsGXlOtmzxXM4JqT',
                    'title' => 'LinkedIn (OAuth2)',
                ],
                'github' => [
                    // register your app here: https://github.com/settings/applications
                    'class' => 'nodge\eauth\services\GitHubOAuth2Service',
                    'clientId' => 'ecd316ad981cb9dd3cb8',
                    'clientSecret' => 'da960d648dd8bdfa9d67b5a3594ae729a3c25ed6',
                ],
//                'live' => [
//                    // register your app here: https://account.live.com/developers/applications/index
//                    'class' => 'nodge\eauth\services\LiveOAuth2Service',
//                    'clientId' => '...',
//                    'clientSecret' => '...',
//                ],
//                'steam' => [
//                    'class' => 'nodge\eauth\services\SteamOpenIDService',
//                //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
//                ],
//                'instagram' => [
//                    // register your app here: https://instagram.com/developer/register/
//                    'class' => 'nodge\eauth\services\InstagramOAuth2Service',
//                    'clientId' => '...',
//                    'clientSecret' => '...',
//                ],
                'vkontakte' => [
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'app\modules\social\models\service\VkontakteService',
                    'clientId' => '5129413',
                    'clientSecret' => 'XZPCpX2GgjlTb8ShaBC3',
                ],
//                'mailru' => [
//                    // register your app here: http://api.mail.ru/sites/my/add
//                    'class' => 'nodge\eauth\services\MailruOAuth2Service',
//                    'clientId' => '...',
//                    'clientSecret' => '...',
////                ],
//                'odnoklassniki' => [
//                    // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
//                    // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
//                    'class' => 'nodge\eauth\services\OdnoklassnikiOAuth2Service',
//                    'clientId' => '...',
//                    'clientSecret' => '...',
//                    'clientPublic' => '...',
//                    'title' => 'Odnoklas.',
//                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'eauth' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@eauth/messages',
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'modules' => [
        'angularjs' => [
            'class' => 'app\modules\angularjs\Module',
        ],
        'social' => [
            'class' => 'app\modules\social\Module',
        ],
        'yandexseo' => [
            'class' => 'app\modules\yandexseo\Module',
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'password' => 'seo',
            'ipFilters' => array('127.0.0.1', '::1'),
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module'
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

if (file_exists(__DIR__ . '/web-local.php')) {
    $localConfig = require 'web-local.php';
    $config = \yii\helpers\ArrayHelper::merge($config, $localConfig);
}
$eauthServices = array_keys($config['components']['eauth']['services']);
array_unshift($config['components']['urlManager']['rules'], array(
    'route' => 'site/sociallogin',
    'pattern' => 'login/<service:(' . implode('|', $eauthServices) . ')>',
));

return $config;
