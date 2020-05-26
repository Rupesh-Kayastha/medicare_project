<?php
Yii::setAlias('root', dirname(dirname(__DIR__)));
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('company', dirname(dirname(__DIR__)) . '/company');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('storage', dirname(dirname(__DIR__)) . '/storage');
Yii::setAlias('api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('frontendUrl', 'http://onestoppharma.in');
Yii::setAlias('backendUrl', 'http://onestoppharma.in/backend');
Yii::setAlias('companyUrl', 'http://onestoppharma.in/company');
Yii::setAlias('storageUrl', 'http://onestoppharma.in/storage');
Yii::setAlias('apiUrl', 'http://onestoppharma.in/api');
define('STATIC_OTP', False);
define('SHIPROCKET_USER', "info@krititech.in");
define('SHIPROCKET_PASSWORD', "qwerty");
?>

