<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
use kartik\widgets\SwitchInput;
use yii\helpers\Url;
use backend\widgets\TinyMCECallback;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
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

        <div class="page-form">
             <?php $form = ActiveForm::begin(); ?>

            <div class="row">	
				
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Page Details')),
								'icon' => 'users',
							]
						)
					?> 
                    <?= $form->field($model, 'status')->widget(SwitchInput::classname(), []) ?>
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
                    <?php Panel::end() ?>
                </div>
                
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                
                    <?php 
                            Panel::begin(
                                [
                                    'header' => Html::encode(Yii::t('backend', 'Meta Info')),
                                    'icon' => 'users',
                                ]
                            )
                    ?>
                     <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
                    
                    <?php Panel::end() ?>
                </div>
            </div>
            <div class="row">	
				
				<div class="form-group col-md-12 col-sm-12 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Page Details')),
								'icon' => 'users',
							]
						)
					?> 
                   
                     <?= $form->field($model, 'body')->widget(TinyMce::class, [
                        'language' => strtolower(substr(Yii::$app->language, 0, 2)),
                        'clientOptions' => [
                            'height'=> 350,
                            'plugins' => [
                                'advlist autolink lists link image charmap print preview anchor pagebreak',
                                'searchreplace visualblocks code fullscreen',
                                'insertdatetime media table contextmenu paste code textcolor colorpicker',
                            ],
                            'toolbar' => 'undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor',
                            'file_picker_callback' => TinyMCECallback::getFilePickerCallback(['file-manager/frame']),
                        ],
                    ]) ?>

                   
                    <?php Panel::end() ?>
                </div>
            </div>
               

                

            


            <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>


            <?php ActiveForm::end(); ?>

        </div>



        <?php Panel::end() ?> 
    </div>
</div>

