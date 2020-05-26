<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
use kartik\widgets\SwitchInput;


/* @var $this yii\web\View */
/* @var $model common\models\Company */
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

        <div class="company-form">

            <?php $form = ActiveForm::begin(); ?>
			<div class="row">	
				
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Company Details')),
								'icon' => 'users',
							]
						)
					?> 
						<?= $form->field($model, 'ActiveStatus')->widget(SwitchInput::classname(), []) ?>
						
						<?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>
						
						<?= $form->field($model, 'AddressLine1')->textInput(['maxlength' => true]) ?>

						<?= $form->field($model, 'AddressLine2')->textInput(['maxlength' => true]) ?>

						<?= $form->field($model, 'LandMark')->textInput(['maxlength' => true]) ?>

						<?= $form->field($model, 'State')->textInput(['maxlength' => true]) ?>

						<?= $form->field($model, 'City')->textInput(['maxlength' => true]) ?>

						<?= $form->field($model, 'Zipcode')->textInput() ?>

						<?= $form->field($model, 'ContactNo')->textInput() ?>
					<?php Panel::end() ?>
				</div>
				
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Logo Branding')),
								'icon' => 'users',
							]
						)
					?> 
						<?php 
							echo $form->field($model, 'Logo')->widget(InputFile::className(), [
								'language'      => 'en',
								'controller'    => 'elfinder', 
								'filter'        => 'image',   
								'template'      => '<div class="input-group"><span>{image}</span><span class="input-group-btn" style="vertical-align:top;">{button}</span>{input}</div>',
								'options'       => ['class' => 'form-control','type'=>'hidden'],
								'buttonOptions' => ['class' => 'btn btn-default'],
								'multiple'      => false,
								'defaultImage' 	=> Yii::getAlias('@storageUrl').'/default/default_company.png',
								
							]);
						?>
						
					<?php Panel::end() ?>
					
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Supper Admin Credential')),
								'icon' => 'user',
							]
						)
					?>
					<?= $form->field($empmodel, 'EmployeeRoleId')->hiddenInput(['value'=>1])->label(false); ?>
					<?= $form->field($empmodel, 'EmpId')->textInput(['maxlength' => true,'disabled'=>($model->isNewRecord?false:true)]); ?>
					
					<?= $form->field($empmodel, 'EmailId')->textInput(['maxlength' => true]) ?>
					
					<?= $form->field($empmodel, 'ContactNo')->textInput() ?>
					
					<?= $form->field($empmodel, 'Password')->textInput(['value'=>'','maxlength' => true]) ?>
					
					<?= $form->field($empmodel, 'password_repeat')->textInput(['value'=>'','maxlength' => true]) ?>
					
					<?php Panel::end() ?>
				</div>
				
				<div class="clearfix"></div>
			</div>
				
				

				
				

				


            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

