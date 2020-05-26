<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    

    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

                <p class="text-center">
                    If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
                </p>
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'Name')->textInput(['autofocus' => true]) ?>

                 <?= $form->field($model, 'Mob') ?>

                <?= $form->field($model, 'Email') ?>

                <?= $form->field($model, 'Subject') ?>

                <?= $form->field($model, 'Message')->textarea(['rows' => 6]) ?>

                

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
