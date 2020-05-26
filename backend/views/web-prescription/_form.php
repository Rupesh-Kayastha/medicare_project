<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\WebPrescription */
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

        <div class="web-prescription-form">

            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'Prescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'IsDelete')->textInput() ?>

    <?= $form->field($model, 'OnDate')->textInput() ?>

    <?= $form->field($model, 'UpdatedDate')->textInput() ?>


            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

