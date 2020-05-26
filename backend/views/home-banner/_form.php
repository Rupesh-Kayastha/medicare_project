<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;


/* @var $this yii\web\View */
/* @var $model common\models\HomeBanner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <?php 
        Panel::begin(
            [
                'header' => Html::encode($this->title),
                'icon' => 'users',
            ]
        )
         ?> 

        <div class="home-banner-form">

            <?php $form = ActiveForm::begin(); ?>

            
            <?= $form->field($model, 'BannerImage')->fileInput() ?>

            <?= $form->field($model, 'WebLink')->textInput(['maxlength' => true]) ?>

            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

