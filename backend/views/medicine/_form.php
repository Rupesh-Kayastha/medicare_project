<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
use kartik\widgets\SwitchInput;
use common\models\MedicineCategory;
use kartik\tree\TreeViewInput;


/* @var $this yii\web\View */
/* @var $model common\models\Medicine */
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

        <div class="medicine-form">

            <?php $form = ActiveForm::begin(); ?>
				
				
				<div class="row">
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
					
						<?php 
							Panel::begin(
								[
									'header' => Yii::t('backend', 'Medicine Details'),
									'icon' => 'money',
								]
							)
						?> 
							<?= $form->field($model, 'Name')->textInput(['maxlength' => true]) ?>

							<div class="form-group field-BrandName required">
								<label class="control-label" for="BrandName"><?= Yii::t('backend', 'Brand Name')?></label>
								<?php
								 echo \yii\jui\AutoComplete::widget([
									'name' => 'BrandName',
									'id' => 'BrandName',
									'options'=>['class'=>'form-control','required'=>'required'],
									'clientOptions' => [
										'source' => $Brands,
										'minLength'=>'3',
										'select' => new JsExpression("function( event, ui ) {
												$('#medicine-brandid').val(ui.item.id);
											}"),
										
										],
									 ]);
								?>

								<div class="help-block"></div>
							</div>
							<?= Html::activeHiddenInput($model, 'BrandId')?>
							<?= $form->field($model, 'MediceneImage')->widget(InputFile::className(), [
								'language'      => 'en',
								'controller'    => 'elfinder', 
								'filter'        => 'image',
								'path'			=> '/medicene_images/',							
								'template'      => '<div class="input-group"><span>{image}</span><span class="input-group-btn" style="vertical-align:top;">{button}</span>{input}</div>',
								'options'       => ['class' => 'form-control','type'=>'hidden'],
								'buttonOptions' => ['class' => 'btn btn-default'],
								'multiple'      => false,
								'defaultImage' 	=> Yii::getAlias('@storageUrl').'/default/default_category.png',
								
							]);
							?>
						<?php Panel::end() ?>
						
							<?php 
								Panel::begin(
									[
										'header' => Yii::t('backend', 'Pricing'),
										'icon' => 'money',
									]
								)
							?> 
								<?= $form->field($model, 'RegularPrice')->textInput() ?>
								
								<?= $form->field($model, 'DiscountedPrice')->textInput() ?>
							<?php Panel::end() ?> 
						
						
							<?php 
								Panel::begin(
									[
										'header' => Yii::t('backend', 'Setting'),
										'icon' => 'cogs',
									]
								)
								 ?>
								<?= $form->field($model, 'IsPrescription')->widget(SwitchInput::classname(), []);?>
								
								<?= $form->field($model, 'InStock')->widget(SwitchInput::classname(), []);?>

								<?= $form->field($model, 'BestSeller')->widget(SwitchInput::classname(), []);?>
							<?php Panel::end() ?> 

						
					</div>
					<div class="form-group col-md-6 col-sm-6 col-xs-12">
						<?php 
							Panel::begin(
								[
									'header' => Yii::t('backend', 'Category'),
									'icon' => 'money',
								]
							)
						?>
							<?= $form->field($model, 'MedicineCategoryId')->widget(TreeViewInput::classname(),
											[
																					
											'query' =>MedicineCategory::find()->addOrderBy('root, lft'),
											'headingOptions' => ['label' => 'Categories'],
											'rootOptions' => ['label'=>'<span class="text-primary">Categories</span>'],
											'rootNodeCheckboxOptions'=>['class'=>'hide'],
											'topRootAsHeading' => true,
											'fontAwesome' => true,
											'asDropdown' => false,
											'multiple' => true,

											'options' => ['disabled' => false]
											])->label(false);
							?>
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
<?php 
$script=<<<JS

$('#BrandName').on('input',function () {
	$('#medicine-brandid').val('');                     
});
JS;
$this->registerJs($script);

if($model->BrandId){
	
	$brandName=$model->brand->Name;
	$brandAutoSelectInUpdateLoad=<<<JS
	$('#BrandName').val('$brandName').trigger('select');
JS;

	$this->registerJs($brandAutoSelectInUpdateLoad);
}
?>
