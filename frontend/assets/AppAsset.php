<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/chat.css',
		'css/alertify.css',
        'css/default.css',
        'css/owl-carousel.css',
        
    ];
    public $js = [
        'js/chat.js',
        'js/alertify.js',
        'js/custom.js',
        'js/sha.js',
        'js/owl-carousel.js',
		
    ];
    public $depends = [
		'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
		'yii\bootstrap\BootstrapThemeAsset',
		'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
