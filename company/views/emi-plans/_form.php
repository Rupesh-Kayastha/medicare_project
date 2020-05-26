<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use yii\web\JsExpression;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\EmiPlans */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12">
        <?php 
        Panel::begin(
            [
                'header' => Html::encode($this->title),
                'icon' => 'rupee',
            ]
        )
         ?> 

        <div class="emi-plans-form">
			<?php $form = ActiveForm::begin(); ?>
			<div class="row">	
				
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('company', 'Plan Details')),
								'icon' => 'calculator',
							]
						)
					?> 

				  
					<?= $form->field($model, 'EmiPlanCompanyId')->hiddenInput(['value'=>Yii::$app->user->identity->CompanyId])->label(false); ?>
					
					<?= $form->field($model, 'EmiPlanStatus')->widget(SwitchInput::classname(), []) ?>

					<?= $form->field($model, 'EmiPlanName')->textInput(['maxlength' => true]) ?>
					<?php 
					$EmiPlanPeriod=[
					2=>'2',
					3=>'3',
					4=>'4',
					5=>'5',
					6=>'6',
					7=>'7',
					8=>'8',
					9=>'9',
					10=>'10',
					11=>'11',
					12=>'12',
					
					]
					
					
					
					?>
					<?= $form->field($model, 'EmiPlanPeriod')->dropDownList(
									$EmiPlanPeriod,           
									['prompt'=>'Select Plan Period']
								);?>

					<?= $form->field($model, 'EmiPlanOrderMinAmount')->textInput() ?>

					<?= Html::submitButton($model->isNewRecord ? Yii::t('company', 'Create') : Yii::t('company', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


					<?php Panel::end() ?>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
        </div>



        <?php Panel::end() ?> 
    </div>
</div>

