<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $model company\models\search\EmiSchedulesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="emi-schedules-search">
 <?php         Panel::begin(
        [
        'header' => Html::encode("Search"),
        'icon' => 'users',
		
        ]
        )
         ?>
			
    <?php 
	$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
	
	
	<div class="row">
		<div class="col-md-4 col-xs-4">
		<?= $form->field($model, 'EmpId') ?>
		<?= $form->field($model, 'OrderIdentifier') ?>
		</div>
		<div class="col-md-4 col-xs-4">
			<?= $form->field($model, 'EmiMonth')->widget(DatePicker::classname(), [
                   // 'options' => ['placeholder' => Yii::t('app', 'EMI Month')],
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView'=>'year',
                        'minViewMode'=>'months',
                        'format' => 'M yyyy'
                    ]
                ])?>
			<?= $form->field($model, 'EmiAmount') ?>
		</div>
		<div class="col-md-4 col-xs-4">
			<?= $form->field($model, 'EmiClearingStatus')->dropDownList(
            [0=>'Not Clear',1=>'Cleared'],           // Flat array ('id'=>'label')
            ['prompt'=>'']    // options
        ); ?>
			
		</div>
		<div class="col-md-12 col-xs-12">
			<div class="form-group pull-right">
				<?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
				<?= Html::a(Yii::t('backend', 'Reset'),["emi-schedules/"],['class' => 'btn btn-default']) ?>
			</div>		
		</div>
    </div>
	 
    <?php ActiveForm::end(); ?>
	<?php Panel::end() ?>
</div>
