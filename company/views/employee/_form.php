<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use kartik\date\DatePicker;
use common\models\EmployeeRole;
use yii\web\JsExpression;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model common\models\Employee */
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

        <div class="employee-form">

            <?php $form = ActiveForm::begin(); ?>
			<div class="row">	
				
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('company', 'Employee Details')),
								'icon' => 'users',
							]
						)
					?> 
						<?= $form->field($model, 'CompanyId')->hiddenInput(['value'=>Yii::$app->user->identity->CompanyId])->label(false); ?>
						
						<?= $form->field($model, 'EmployeeActiveStatus')->widget(SwitchInput::classname(), []) ?>
						
						<?= $form->field($model, 'EmployeeName')->textInput(['maxlength' => true]) ?>
						
						<?= $form->field($model, 'EmpId')->textInput(['maxlength' => true]) ?>

						<?= $form->field($model, 'ContactNo')->textInput() ?>

						<?= $form->field($model, 'EmailId')->textInput(['maxlength' => true]) ?>
						
						<?= $form->field($model, 'Dob')->widget(DatePicker::classname(), [
								'options' => ['placeholder' => 'Enter birth date.'],
								'type' => DatePicker::TYPE_COMPONENT_APPEND,
								'readonly' => true,
								'pluginOptions' => [
									
									'autoclose'=>true,
									'format' => 'yyyy-mm-dd'
								]
							]);
						?>
						<?= $form->field($model, 'BloodGroup')->textInput(['maxlength' => true]) ?>

						

					<?php Panel::end() ?>
				</div>
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('company', 'Role & Credential')),
								'icon' => 'users',
							]
						)
					?> 
					
						<?= $form->field($model, 'EmployeeRoleId')->dropDownList($EmployeeRole,['prompt'=>"Select Role",'onchange'=>'showPassword(this.value)']) ?>
						
						
						<?php 
						
						$calssMap="";
						if($model->scenario =='updateEmployee' && $model->EmployeeRoleId==EmployeeRole::NORMAL_EMPLOYEEE){
							$calssMap="hidden";

						}
						if($model->scenario =='create'){
							$calssMap="hidden";
						}
						
						if($model->scenario =='updateEmployee'){
							
							echo Html::hiddenInput('',$model->EmployeeRoleId,['id' =>"old_roleId"]);
						}
						
						
						?>
						
						<?= $form->field($model, 'Password', [ 'options' => [ 'class' => "$calssMap form-group"]])->textInput(['value'=>'','maxlength' => true]) ?>

						<?= $form->field($model, 'password_repeat',[ 'options' => [ 'class' => "$calssMap form-group"]])->textInput(['value'=>'','maxlength' => true]) ?>

						
					<?php Panel::end() ?>
				</div>
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
                <?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('company', 'User Credit')),
								'icon' => 'money',
							]
						)
					?> 
                    <?= $form->field($model, 'CreditLimit')->textInput(['type' => 'number']) ?>
                    <?php Panel::end() ?>
                </div>
				<div class="clearfix"></div>
			</div>

            <?= Html::submitButton($model->isNewRecord ? Yii::t('company', 'Create') : Yii::t('company', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>
<script>
function showPassword(roleval){
	
	console.log("roleval: "+roleval);
	if(roleval){
		if(roleval!=4){
			$(".field-employee-password").removeClass("hidden").addClass("show");
			$(".field-employee-password_repeat").removeClass("hidden").addClass("show");
		}
		else {
			$("#employee-password").val("");
			$("#employee-password_repeat").val("");
			$(".field-employee-password").removeClass("show").addClass("hidden");
			$(".field-employee-password_repeat").removeClass("show").addClass("hidden");
			
		}
	}
	else{
		$("#employee-password").val("");
		$("#employee-password_repeat").val("");
		$(".field-employee-password").removeClass("show").addClass("hidden");
		$(".field-employee-password_repeat").removeClass("show").addClass("hidden");
		
	}
}
</script>
