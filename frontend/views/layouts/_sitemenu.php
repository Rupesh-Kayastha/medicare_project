<?php 
use yii\helpers\Url;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use frontend\models\MedicineCategory;

echo Nav::widget([
	'options' => ['class' => 'nav navbar-nav site_nav_menu1'],
    'items' => 
        array_merge(
        	[['label' => 'Home', 'url' => ['/'],'options'=>['class'=>'first active']]],
        	MedicineCategory::getFullTreeInline(),
            [['label' => 'Emergency', 
                'items' => [['label' => 'Govt.Hospitals', 'url' => ['site/govthospital']],
                            ['label' => 'Pvt.Hospitals', 'url' => ['site/pvthospital']],
                            ['label' => 'Ambulance', 'url' => ['site/ambulance']],
                            ['label' => 'Blood bank', 'url' => ['site/bloodbank']]],
                            'options'=>['class'=>'first']]]
            //[['label' => 'Upload Prescription', 'url' => ['site/uploadprescription'],'options'=>['class'=>'first']]]
),
        
    
    

]);

$script=<<<JS
jQuery(function($) {
$('.navbar .dropdown').hover(function() {
$(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();

}, function() {
$(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();

});

$('.navbar .dropdown > a').click(function(){
location.href = this.href;
});

});
JS;
$this->registerJs($script);
?>
