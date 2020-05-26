<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Upload Prescription';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
label {font-weight:normal;}
</style>
<div class="site-precription">
    

    <div class="row" style="position:relative;">
	<div class="contentText">
		 <h1 ><?= Html::encode($this->title) ?></h1>
	</div>
        <div class="col-lg-6">
           
                <?php $form = ActiveForm::begin(['id' => 'precription-form']); ?>
                    <?= $form->field($model, 'UserName') ?>

                    <?= $form->field($model, 'UserContact') ?>

                    <?= $form->field($model, 'UserMail') ?>

                    <?= $form->field($model, 'UserAddress')->textarea(['rows' => 4]) ?>

                    <?= $form->field($model, 'UserMessage')->textarea(['rows' => 4]) ?>

                     <?= $form->field($model, 'Prescription')->fileInput() ?>
                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'prescription-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
        </div>
		<div class="col-lg-6" style="height:500px;">
			<img src="/images/pres.png" class="img-responsive" style="position:absolute; bottom:0px;"/>
		</div>
    </div>

</div>
