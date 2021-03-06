<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
				<h1><?= Yii::t('company', "Company Portal") ;?></h1>
				
				<?= $form->field($model, 'CompanyId')->dropDownList($companies,['prompt'=>"Select Company"])->label(false); ?>
				
                <?= $form->field($model, 'EmpId')->textInput()->input('text', ['placeholder' => "Enter Your Username"])->label(false); ?>

                <?= $form->field($model, 'Password')->passwordInput()->input('Password', ['placeholder' => "Enter Your Password"])->label(false); ?>

               
                <div class="form-group">
                     <?= Html::submitButton('Login', ['class' => 'btn btn-default submit', 'name' => 'login-button']) ?>
                </div>
				
				<div class="clearfix"></div>

            		
				<div class="separator" style="border:none;">
					<div>
					  <h1><i class="fa fa-medkit"></i> <?= Yii::t('company', Yii::$app->name) ;?>!</h1>
					  <p>©2019 All Rights Reserved. <?= Yii::t('company', Yii::$app->name) ;?>!</p>
					</div>
				</div>
            <?php ActiveForm::end(); ?>
   