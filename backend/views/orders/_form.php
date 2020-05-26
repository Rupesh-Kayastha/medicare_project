<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
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

        <div class="orders-form">

            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'CartIdentifire')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'EmployeeId')->textInput() ?>

    <?= $form->field($model, 'OrderTotalPrice')->textInput() ?>

    <?= $form->field($model, 'PaymentType')->textInput() ?>

    <?= $form->field($model, 'DeliveryAddressID')->textInput() ?>

    <?= $form->field($model, 'EmiPlanId')->textInput() ?>

    <?= $form->field($model, 'EmiPlanPeriod')->textInput() ?>

    <?= $form->field($model, 'EmiAmount')->textInput() ?>

    <?= $form->field($model, 'CreditBalanceUsed')->textInput() ?>

    <?= $form->field($model, 'OrderType')->textInput() ?>

    <?= $form->field($model, 'CreatedDate')->textInput() ?>

    <?= $form->field($model, 'UpdatedDate')->textInput() ?>

    <?= $form->field($model, 'IsDelete')->textInput() ?>


            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

