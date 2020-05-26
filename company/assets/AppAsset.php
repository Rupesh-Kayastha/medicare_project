<?php

namespace company\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
    ];
    public $js = [
		'js/autocomplete.ex.js?x'
    ];
    public $depends = [
		'yii\web\YiiAsset',
        'yiister\gentelella\assets\Asset'
    ];
}
