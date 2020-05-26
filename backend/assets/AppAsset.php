<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/chat.css',
        'css/alertify.css',//alertify alert
        'css/default.css',//alertify alert
    ];
    public $js = [
		'js/autocomplete.ex.js?x',
        'js/chat.js',
        'js/alertify.js',//alertify alert
        'js/jquery.table2excel.js',
    ];
    public $depends = [
        'yiister\gentelella\assets\Asset'
    ];
}
