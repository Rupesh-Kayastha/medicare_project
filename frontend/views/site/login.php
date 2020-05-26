<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-sm-12" id="content">            
	<div class="breadcrumbs">
		<a href="<?= Url::home(); ?>"><i class="fa fa-home"></i></a>
		<a href="#">Login</a>
	</div>
	<div class="contentText">
		<h1 class="text-center">Welcome to Arundaya Medicare Pvt. Ltd. Digital Platform</h1>
		<div class="row">
			
			<div class="col-sm-6 col-sm-offset-2">
				<!--<div class="well">!-->
				
				<strong class="has-error"><span class="control-label" id="status_msg">&nbsp;</span></strong>
				<!-- <h2>Login</h2> -->
				<?php $form = ActiveForm::begin([
					'id' => 'login-form',
					'options' =>[
						'class'=>'form-horizontal add_margin',
						
					]]);
				?>
				
					<?= $form->field($model, 'CompanyId',[
					'template' => "{label}\n<div class='col-sm-8'>{input}\n{hint}\n{error}</div>",
					'labelOptions' => [ 'class' => 'control-label col-sm-4' ]
					])->dropDownList($companies,['prompt'=>"Select Company"]); ?>
				
					<?= $form->field($model, 'EmpId',[
					'template' => "{label}\n<div class='col-sm-8'>{input}\n{hint}\n{error}</div>",
					'labelOptions' => [ 'class' => 'control-label col-sm-4' ]
					] )->textInput() ?>
					
					<div class="form-group <?= ($model->EmpId)?'hide':'show' ?>" id="otp_btn_box_send">
						<div class="col-sm-12">
							<button id="otp_btn_send" class="btn btn-primary reg_button pull-right"  type="button">
								<i class="fa fa-key"></i>&nbsp;
								<span>Send OTP</span>                            
							</button>

						</div>
					</div>
					
					<div class="form-group <?= ($model->EmpId)?'show':'hide' ?>" id="otp_btn_box_resend">					
						<div class="col-sm-12">							
							<button id="otp_btn_resend" class="btn btn-primary reg_button pull-right"  type="button">
								<i class="fa fa-key"></i>&nbsp;
								<span>Resend OTP</span>                            
							</button>
							<span class="pull-right">
								Didn't Get OTP Yet. 
							</span>
						</div>
					</div>
					
					<?= $form->field($model, 'OtpHash',[
					'template' => "{label}\n<div class='col-sm-8'>{input}\n{hint}\n{error}</div>",
					'labelOptions' => [ 'class' => 'control-label col-sm-4' ],
					'options'=>['class'=> (($model->EmpId)?'show':'hide') .' form-group']
					])->textInput(['value'=>'']) ?>
					
					<div class="<?= ($model->EmpId)?'show':'hide'?> form-group field-loginform-submit">
						<div class="col-sm-12">
							<?= Html::submitButton('<i class="fa fa-key"></i>&nbsp;Login', ['class' => 'btn btn-primary reg_button pull-right', 'name' => 'login-button']) ?>
						</div>
					</div>
					
				
				<?php ActiveForm::end(); ?>
				<!--</div>!-->
			</div>
			<!-- <div style="border-left: 1px dashed #c1bebe" class="col-sm-6">
				
				<h2><?= Html::encode(Yii::$app->name) ?></h2>
			   
				<p> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
				
				
			</div> -->
		</div>
	</div>
</div>

<?php
$ajaxUrl=Url::to(['site/generate-otp']);

$script=<<<JS
    
	
	function GenerateOtp(){
		$("#status_msg").html("&nbsp;");
		$("#loginform-otphash").val("");
		$.ajax({
			  method: "POST",
			  url: "$ajaxUrl",
			  data: { LoginForm:{'CompanyId': $("#loginform-companyid").val(), 'EmpId': $("#loginform-empid").val()} }
			})
			.done(function( data ) {
				
				var json = $.parseJSON(data);
				
				$("#status_msg").html(json.message);
				if(json.status){
									
					
				}
				else{
					$("#otp_btn_box_resend").removeClass("show").addClass("hide");
					$(".field-loginform-otphash").removeClass("show").addClass("hide");
					$(".field-loginform-submit").removeClass("show").addClass("hide");
					$("#otp_btn_box_send").removeClass("hide").addClass("show");
					$("#loginform-companyid").attr("readonly",false);
					$("#loginform-empid").attr("readonly",false);
					$("#loginform-companyid").val("");
					$("#loginform-empid").val("");
					
				}
			});
		
	}
	$(document).on("click","#otp_btn_resend",GenerateOtp);
	
	$(document).on("click","#otp_btn_send",function(){
            var form = $("#login-form");
            var data = form.data("yiiActiveForm");
            $.each(data.attributes, function() {
				
                this.status = 3;
				
            });
            form.yiiActiveForm("validate");
			
		 if (form.find(".has-error").length) {
			  return false;
		 }
		 else{
			 $("#otp_btn_box_resend").removeClass("hide").addClass("show");
			 $(".field-loginform-otphash").removeClass("hide").addClass("show");
			 $(".field-loginform-submit").removeClass("hide").addClass("show");
			 $("#otp_btn_box_send").removeClass("show").addClass("hide");
			 $("#loginform-companyid").attr("readonly",true);
			 $("#loginform-empid").attr("readonly",true);
			 			 
			 GenerateOtp();
			 
		 }
			
    });
	
	$("#login-form").submit(function(e) {
		var otp = $('#loginform-otphash').val();
		if(!otp){
		e.preventDefault(); 
	    e.stopImmediatePropagation();
		$("#otp_btn_send").click();
		}	
		
	});
	
	
	
JS;
$this->registerJs($script);
?>