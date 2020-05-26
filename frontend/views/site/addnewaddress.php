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
            ]
        )
         ?> 

        <div class="orders-form">

            <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'AddressLine1')->textInput() ?>

    <?= $form->field($model, 'AddressLine2')->textInput() ?>

    <?= $form->field($model, 'LandMark')->textInput() ?>

    <?= $form->field($model, 'State')->textInput() ?>

    <?= $form->field($model, 'City')->textInput() ?>

    <?= $form->field($model, 'Zipcode')->textInput(['type'=>'number']) ?>

    <?= $form->field($model, 'ContactNo')->textInput(['type'=>'number']) ?>

	<?= Html::submitButton('submit', ['class' => 'btn btn-primary']) ?>

	<?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

