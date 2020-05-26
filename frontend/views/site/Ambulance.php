<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Ambulance';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.listbox{background#fff; padding:10px; box-shadow: 5px 5px 5px 0px gainsboro !important; margin-bottom:5px; border-radius:5px; border:1px solid #dedede;}
</style>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

  <?php
        $i=0;
            foreach($Ambulance as $key=>$value)
            {
            ?>
            <p class="listbox" style="width:48%; float:left; margin-right:1%;"><i class="fa fa-ambulance" style="color:#222; font-size:15px;"></i> <a style="color:#33a4d8;"><?=$value->HospitalName?></a>  <i class="fa fa-phone" style="color:#222; font-size:15px;"></i> <span style="color:#CC3333;"><?=$value->Phone_Number?></span></p>
           
             <?php
             $i++;
            }
             ?>

 
</div>
