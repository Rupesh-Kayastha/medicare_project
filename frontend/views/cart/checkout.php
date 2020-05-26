<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use edwinhaq\simpleloading\SimpleLoading;
SimpleLoading::widget();

$this->title = 'Checkout';
//echo "<pre>"; var_dump($employeeaddress);
?>
<style type="text/css">
.address_card{margin-top: 15px; margin-bottom:15px;}
.panel-body p{padding:8px; line-height:1.8; font-size:14px; color:#666;}
table tr th{background:#2a5b86;color:#fff; font-weight:normal;}
table tr td{vertical-align:middle !important;font-size: 16px;
color: #555;}
.delivery_address {line-height:1.7; color:#666;}
</style>
<div class="main-content">
    <div class="container checkout">
		<div class="contentText">
        	<h1>Checkout</h1>
        </div>
		<div id="accordion" class="panel-group margin-top">
			<!-- Login -->
			<div class="panel panel-default" style="display:<?=$display;?>;">
				<div class="panel-heading">
					<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-checkout-option">Login <i class="fa fa-caret-down"></i></a></h4>
				</div>
				<div id="collapse-checkout-option" class="panel-collapse collapse in" aria-expanded="false">
					<div class="panel-body"><div class="row">
							<div class="col-sm-6">
								<?php $form = ActiveForm::begin([
									'action' => ['site/login'],
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
							</div>
							<div class="col-sm-6">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Delivery Details -->
			<div class="panel panel-default delivery_details" onclick="checkPrevious(2)" style="display:<?=$empaddre;?>;">
				<div class="panel-heading">
					<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-shipping-address" aria-expanded="true">Delivery Details <i class="fa fa-caret-down"></i></a></h4>
				</div>
				<div id="collapse-shipping-address" class="panel-collapse collapse <?=$second;?>" aria-expanded="true" style="">
					<div class="panel-body address_data">
						<?php 
						if($employeeaddress!=''){
							foreach($employeeaddress as $key => $value){
						?>
						<div class="col-md-3 col-xs-3 address_card">
							<div class="panel panel-success">
								<div class = "panel-heading">
									<h3 class = "panel-title"><i class="fa fa-address-book"></i> ADDRESS</h3>
								</div>
								<div class = "panel-body">
									<p>
										<i class="fa fa-address-card-o"></i> <?=$value->AddressLine1;?><br/>
											<?=$value->AddressLine2;?><br/>
										<i class="fa fa-landmark"></i><?=$value->LandMark;?><br/>
										<i class="fa fa-flag"></i> State: <?=$value->State;?><br/>
										<i class="fa fa-building-o"></i> City: <?=$value->City;?><br/>
										<i class="fa fa-map-pin"></i> Zipcode: <?=$value->Zipcode;?><br/>
										<i class="fa fa-phone"></i> Phone No: <?=$value->ContactNo;?>
									</p>
									
									<center>
										<input type="radio" name="address" id="address" value="<?=$value->EmployeeAddressId?>" onclick="fillNewaddress(this.value);"/>
									</center>
								</div>
							</div>
						</div>
						<?php
							}						
						}
						?>
						<div class="col-md-3 col-xs-3 address_card">
							<div class="panel panel-success">
								<div class = "panel-heading">
									<h3 class = "panel-title"><i class="fa fa-plus"></i> ADD NEW ADDRESS</h3>
								</div>
								<div class = "panel-body">
									<center>
										<input type="radio" name="address" id="address" value="0" onclick="fillNewaddress(this.value);"/>
									</center>
								</div>
							</div>
						</div>
						
					</div>
					
					<div class="panel panel-success address-form" style="display:none; padding:10px; margin:10px;">
						<div class = "panel-heading">
							<center><h3 class = "panel-title">ADD NEW ADDRESS</h3></center>
						</div>
						<div class="panel-body" style="margin-top:10px;">
							<form name="addnewaddress" class="form-horizontal" id="addnewaddress" method="POST" action="<?=Url::to(['cart/addnewaddress'])?>">
								<div class="form-group required">
									<label for="input-shipping-address-1" class="col-sm-2 control-label">Address Line 1</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="input-shipping-address-1" placeholder="Address 1" name="addresline1" required>
									</div>
								</div>
								<div class="form-group">
									<label for="input-shipping-address-2" class="col-sm-2 control-label">Address Line 2</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="input-shipping-address-2" placeholder="Address 2" name="addresline2" required>
									</div>
								</div>
								<div class="form-group">
									<label for="input-shipping-landmark" class="col-sm-2 control-label">Land Mark</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="input-shipping-landmark" placeholder="Enter Land mark" name="landmark" required>
									</div>
								</div>
								<div class="form-group required">
									<label for="input-shipping-state" class="col-sm-2 control-label">State</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="input-shipping-state" placeholder="Enter State" name="state" required>
									</div>
								</div>
								<div class="form-group required">
									<label for="input-shipping-city" class="col-sm-2 control-label">City</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="input-shipping-city" placeholder="City" name="city" required>
									</div>
								</div>
								<div class="form-group required">
									<label for="input-shipping-postcode" class="col-sm-2 control-label">Post Code</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="input-shipping-postcode" placeholder="Post Code" name="postcode" required>
									</div>
								</div>
								<div class="form-group required">
									<label for="input-shipping-contactno" class="col-sm-2 control-label">Contact No.</label>
									<div class="col-sm-10">
										<input type="text" class="form-control" id="input-shipping-contactno" placeholder="Enter Contact No." name="contactno" required>
									</div>
								</div>
								<div class="buttons">
									<div class="pull-right">
										<input type="submit" class="btn btn-primary"  id="button-guest-shipping" value="Save">
									</div>
								</div>
							</form>
						</div>
						
					</div>
					<div class="panel-body" style="padding:5px;">
						<div class="buttons">
							<div class="pull-right">
								<input type="button" class="btn btn-info"  id="button-guest-shipping" value="Continue" onclick="setAddress();">
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- payment method -->
			
			<div class="panel panel-default payment_method" style="display:none;" onclick="checkPrevious(3)">
				<div class="panel-heading">
					<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-payment-method" aria-expanded="true">Payment Method <i class="fa fa-caret-down"></i></a></h4>
				</div>
				<div id="collapse-payment-method" class="panel-collapse collapse" aria-expanded="true" style="">
					<div class="panel-body wallet_balance"  style="padding:5px; text-align:right; margin:5px; "></div>
					<div class="panel-body" style="padding:5px;">
						<p>Please select the preferred payment method to use on this order.</p>
						<div class="radio">
							<div class="col-md-3" style="text-align:center;">
							<label>
								<input type="radio" value="1" name="payment_method" id="cod" onclick="openEmiplan(0)"><br/>
								Cash On Delivery(COD)      
							</label>
							</div>
							<!-- <div class="col-md-3" style="text-align:center;">
							<label>
								<input type="radio" value="2" name="payment_method" id="online"><br/>
								Online Payment      
							</label>
							</div> -->
							<div class="col-md-3" style="text-align:center;">
							<label>
								<input type="radio"  value="3" name="payment_method" id="direct" onclick="openEmiplan(0)"><br/>
								Direct(Company)      
							</label>
							</div>
							<div class="col-md-3" style="text-align:center;">
							<label>
								<input type="radio"  value="4" name="payment_method" id="emi" onclick="openEmiplan(1)"><br/>
								EMI(Company)    
							</label>
							</div>
						</div>
						
					</div>
					<div class="panel-body emidata_parent" style="display:none;"><div class="radio emidata col-md-3" style="text-align:center;"></div></div>
					<div class="panel-body" style="padding:5px;">
						<div class="buttons">
							<div class="pull-right">
								<input type="button" class="btn btn-primary continuebtn"  id="button-guest-shipping" value="Continue" onclick="setPayment();">
							</div>
						</div>
					</div>
				</div>
				<!-- modal start -->
				 <div class="modal fade" id="myModal" role="dialog">
				    <div class="modal-dialog">
				    
				      <!-- Modal content-->
				      <div class="modal-content">
				        <div class="modal-header">
				         <!--  <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				          <h4 class="modal-title">Information</h4>
				        </div>
				        <div class="modal-body">
				          <?php $form = ActiveForm::begin(['action'=>Url::toRoute(['cart/payment'])]) ?>
						    <div class="form-group">
						      <label>Name:</label>
						      <input type="hidden" name="uid" value="<?=Yii::$app->user->identity->EmployeeId;?>">
						      <input type="text" id="unm" readonly="readonly" class="form-control" name="uname">
						    </div>
						    <div class="form-group">
						      <label>Contact No:</label>
						      <input type="number" id="cont" readonly="readonly" class="form-control ucont" name="ucont">
						    </div>
						    <div class="form-group">
						      <label>Email:</label>
						      <input type="email" id="emailid" readonly="readonly" class="form-control" name="uemail">
						    </div>
						    <div class="form-group">
						      <label>Amount:</label>
						      <input type="text" id="wltamnt" readonly="readonly" class="form-control" name="uamnt">
						    </div>
						    <button type="submit" name='continue' class="btn btn-default">Continue</button>
						  <?php ActiveForm::end() ?>
				        </div>
				      </div>
				      
				    </div>
				  </div>
				  <!-- modal end -->
				
			</div>
			<!-- confirm order -->
			<div class="panel panel-default order_confirm" style="display:none;" onclick="checkPrevious(4)">
				<div class="panel-heading">
					<h4 class="panel-title"><a class="accordion-toggle" data-parent="#accordion" data-toggle="collapse" href="#collapse-checkout-confirm" aria-expanded="true">Step 6: Confirm Order <i class="fa fa-caret-down"></i></a></h4>
				</div>
				<div id="collapse-order_confirm" class="panel-collapse collapse" aria-expanded="true" style="">
					<div class="panel-body" style="padding:10px;">
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-left">#</th>
										<th class="text-left">Product Name</th>
										<th class="text-right">Quantity</th>
										<th class="text-right">Unit Price</th>
										<th class="text-right">Total Price</th>
									</tr>
								</thead>
								<tbody id="cartdetails"></tbody>
								<tfoot>
								    <tr>
										<td class="text-right" colspan="3">Payment Type:</td>
										<td class="text-right" colspan="2" id="pt">&nbsp;</td>
									</tr>
									<tr>
										<td class="text-right" colspan="3">Order Total Price:</td>
										<td class="text-right" colspan="2" id="otp">&nbsp;</td>
									</tr>
									
								</tfoot>
							</table>
						</div>
						<div class="panel-body">
							<div class="col-md-12">
								<h4 style="color:#e7a042;">Delivery Details</h4>
							</div>
							<div class="col-md-12 delivery_address"></div>
						</div>
						<div class="buttons">
							<div class="pull-right">
								<input type="button"  class="btn btn-primary" id="button-confirm" value="Confirm Order" onclick="confirmOrder();">
							</div>
						</div>

					</div>
				</div>
			</div>
		
		</div>
    </div>
</div>
<?php
$script=<<<JS

$('#search_manu').hide();
$(".continuebtn").click(function(){
        if($('#online').is(':checked')) { 
        $("#myModal").modal('show');
        $('#myModal').modal({backdrop: 'static', keyboard: false})  ;
        }

    });

JS;
$this->registerJs($script);
?>

<script type="text/javascript">


function fillNewaddress(val){
	if($("input:radio[name='address']").is(":checked")) {
		if(val == 0){
			$('.address-form').show('slow');
		}else{
			$('.address-form').hide('slow');
		}
	}
}
function checkPrevious(val){
	if(val == 2){
		$('.payment_method').hide();
		$('.order_confirm').hide();
		//$('.delivery_details').show();
	}else if(val == 3){
		$('.order_confirm').hide();
	}
}
function checkPaymentmethod(){
	$.ajax({
		url: "<?= Url::to(['cart/checkpaymentstaus']);?>",
		type: "POST",
		data: {},
		beforeSend:function(json)
		{ 
			SimpleLoading.start('hourglass'); 
		},
		success: function (data) {
			var result = data.result;
			if(result.emistatus == 0){
				$('#emi').attr('disabled','disabled');
			}else{
				$('#emi').removeAttr('disabled');
			}
			if(result.directstatus == 0){
				$('#direct').attr('disabled','disabled');
			}else{
				$('#direct').removeAttr('disabled');
			}
			$('.wallet_balance').html('<i class="fa fa-credit-card" style="color: #17d154; font-size:20px;"></i> Available Credit Balance : Rs. <span style="font-size:18px; color:#2869a8 ">'+convertIndiancurrency(result.available_balance)+'</span>'); 
			$("#wltamnt").attr('value',result.available_balance);
		},
		complete:function(json)
		{
			getEmidetails();
			SimpleLoading.stop();
		},
	});
}
function setAddress(){
	if($("input:radio[name='address']").is(":checked")) {
		var addressid = $("input[name='address']:checked").val();
		$.ajax({
				url: "<?= Url::to(['cart/setaddress']);?>",
				type: "POST",
				data: {
					addressid: addressid,
				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
				success: function (result) {
					if(result.status == 1){
						alertify.alert(result.msg).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						$('.payment_method').show();
						$('#collapse-payment-method').addClass("in");
						$('#collapse-shipping-address').removeClass("in");
						
					}else{
						alertify.alert('There is some error').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
					}
				},
				complete:function(json)
				{
					getEmidetails();
					checkPaymentmethod();
					SimpleLoading.stop();
				},
		});
	}else{
		alertify.alert('Please choose the address').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
	}
}

function getCartdetails(){
	$.ajax({
		url: "<?= Url::to(['cart/cartdetails']);?>",
		type: "POST",
		data: {},
		beforeSend:function(json)
		{ 
			SimpleLoading.start('hourglass'); 
		},
		success: function (data) {
			if(data.status == 1){
				var cartdetails_html = "";
				$('#cartdetails').html('');
				var j = 1;
				$.each(data.result.CartItems,function(i,it){
					cartdetails_html +="<tr>";
					cartdetails_html +="<td class='text-left'>"+j+"</td>";
					cartdetails_html +="<td class='text-left'>"+it.CartItemName+"</td>";
					cartdetails_html +="<td class='text-left'>"+it.CartItemQty+"</td>";
					cartdetails_html +="<td class='text-left'>&#8377; "+convertIndiancurrency(it.UnitPrice)+"</td>";
					cartdetails_html +="<td class='text-left'>&#8377; "+convertIndiancurrency(it.TotalRowPrice)+"</td>";
					cartdetails_html +="</tr>";
					j++;
				});
				$('#otp').html('&#8377; '+convertIndiancurrency(data.result.CartDetails.GrandTotal));
				$('#pt').html(data.result.CartDetails.PaymentType);
				$('#cartdetails').html(cartdetails_html);
				var address = data.result.CartAddress.AddressLine1+"<br/>"+data.result.CartAddress.AddressLine2+'<br/>'+data.result.CartAddress.LandMark+'<br/>'+data.result.CartAddress.State+'<br/>'+data.result.CartAddress.City+'<br/>'+data.result.CartAddress.Zipcode;
				$('.delivery_address').html(address);
				//console.log(data.result.CartAddress.ContactNo);
				
			}
		},
		complete:function(json)
		{
			SimpleLoading.stop();
		},
	});
}
function getEmidetails(){
	$.ajax({
		url: "<?= Url::to(['cart/emipayment']);?>",
		type: "POST",
		data: {
			PaymentType:4,
		},
		beforeSend:function(json)
		{ 
			SimpleLoading.start('hourglass'); 
		},
		success: function (data) {
			var emidatahtml = '';
			$('.emidata_parent').hide();
			$('.emidata').html('');
			if(data.status == 1){
				$.each(data.emidata,function(i,it){
					var attrdisable = '';
					if(it.Status == 1){
						attrdisable = '';
					}else{
						attrdisable = 'disabled';
					}
					emidatahtml += "<label>";
					emidatahtml += "<input type='radio' value='"+it.EmiPlanId+"' name='emiplane' "+attrdisable+" />";
					emidatahtml += it.EmiPlanName;
					emidatahtml += "</label>";
				});
				$('.emidata').html(emidatahtml);
			}
		},
		complete:function(json)
		{
			SimpleLoading.stop();
		},
	});
}
function setPayment(){
	if($("input:radio[name='payment_method']").is(":checked")) {
		var paymentmethod = $("input[name='payment_method']:checked").val();
		
		/*if(paymentmethod==1){
			
			$.ajax({
				url: "<?= Url::to(['cart/setpayment']);?>",
				type: "POST",
				data: {
					PaymentType:paymentmethod,
				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
				success: function (data) {
					alertify.alert('Payment update sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
					getCartdetails();
					$('.order_confirm').show();
					$('#collapse-order_confirm').addClass("in");
					$('#collapse-payment-method').removeClass("in");
				},
				complete:function(json)
				{
					SimpleLoading.stop();
				},
			});
		}


		if(paymentmethod==2){
				$.ajax({
				url: "<?= Url::to(['cart/paymentinfo']);?>",
				type: "POST",
				data: {

				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
				success: function (data) {
					var result=JSON.parse(data);
					console.log(result);
					getCartdetails();
					$("#unm").attr('value',result.EmployeeName);
					$("#cont").attr('value',result.ContactNo);
					$("#emailid").attr('value',result.EmailId);
				},
				complete:function(json)
				{
					SimpleLoading.stop();
				},
			});

		}



		if(paymentmethod==3){
			$.ajax({
				url: "<?= Url::to(['cart/setpayment']);?>",
				type: "POST",
				data: {
					PaymentType:paymentmethod,
				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
				success: function (data) {
					alertify.alert('Payment update sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
					getCartdetails();
					$('.order_confirm').show();
					$('#collapse-order_confirm').addClass("in");
					$('#collapse-payment-method').removeClass("in");
				},
				complete:function(json)
				{
					SimpleLoading.stop();
				},
			});
		}*/

		if(paymentmethod==4){
			//console.log(123);
			if($("input:radio[name='emiplane']").is(":checked")) {
				var emiplanid = $("input[name='emiplane']:checked").val();
				$.ajax({
					url: "<?= Url::to(['cart/updateemidata']);?>",
					type: "POST",
					data: {
						emimethod:4,
						emiplanid: emiplanid,
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (data) {
						if(data.status == 1){
							alertify.alert('Payment update sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
							getCartdetails();
							$('.order_confirm').show();
							$('#collapse-order_confirm').addClass("in");
							$('#collapse-payment-method').removeClass("in");
							
						}
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
				});
			}
			
		}else if(paymentmethod != 4){
			$.ajax({
				url: "<?= Url::to(['cart/setpayment']);?>",
				type: "POST",
				data: {
					PaymentType:paymentmethod,
				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
				success: function (data) {
					alertify.alert('Payment update sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
					getCartdetails();
					$('.order_confirm').show();
					$('#collapse-order_confirm').addClass("in");
					$('#collapse-payment-method').removeClass("in");
				},
				complete:function(json)
				{
					SimpleLoading.stop();
				},
			});
		}else{
		alertify.alert('Please choose the Payment').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
	}
	
	}
}
function confirmOrder(){
	$.ajax({
			url: "<?= Url::to(['cart/placeorder']);?>",
			type: "POST",
			data: {},
			beforeSend:function(json)
			{ 
				SimpleLoading.start('hourglass'); 
			},
			success: function (data) {
				if(data.status == 1){
					alertify.alert('Order placed sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
					location.href = '<?= Url::to(['cart/thankyou']);?>';
				}else{
					alertify.alert(data.msg).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
				}
			},
			complete:function(json)
			{
				SimpleLoading.stop();
			},
	});
}
function openEmiplan(val){
	if(val == 1){
		$('.emidata_parent').show('slow');
		$('.emidata').show('slow');
	}else{
		$('.emidata_parent').hide('slow');
		$('.emidata').hide('slow');
	}
}
</script>
<?php
$script1=<<<JS
$('#addnewaddress').submit(function(e){
	e.preventDefault(); 
	e.stopImmediatePropagation();
	var form = $(this);
	SimpleLoading.start('hourglass');
	$.ajax({
        type: 'POST',
		url: $(this).attr( 'action' ),
		data: form.serialize(),
		}).done(function(data) {
			$('.address_data').html('');
			var address_html = '';
		if(data.status == 1){
			if(data.address!=''){
				$.each(data.address,function(i,it){
					address_html += '<div class="col-md-3 col-xs-3 address_card">';
					address_html += '<div class="panel panel-success">';
					address_html += '<div class = "panel-heading">';
					address_html += '<h3 class = "panel-title">ADDRESS</h3>';
					address_html += '</div>';
					address_html += '<div class = "panel-body">';
					address_html += '<p>'+it.AddressLine1+'</p>';
					address_html += '<p>'+it.AddressLine2+'</p>';
					address_html += '<p>'+it.LandMark+'</p>';
					address_html += '<p>'+it.State+'</p>';
					address_html += '<p>'+it.City+'</p>';
					address_html += '<p>'+it.Zipcode+'</p>';
					address_html += '<p>'+it.ContactNo+'</p>';
					address_html += '<center>';
					address_html += '<input type="radio" name="address" id="address" value="'+it.EmployeeAddressId+'" onclick="fillNewaddress(this.value);"/>';
					address_html += '</center>';
					address_html += '</div>';
					address_html += '</div>';
					address_html += '</div>';
				});
				address_html += '<div class="col-md-3 col-xs-3 address_card">';
				address_html += '<div class="panel panel-success">';
				address_html += '<div class = "panel-heading">';
				address_html += '<h3 class = "panel-title">ADD NEW ADDRESS</h3>';
				address_html += '</div>';
				address_html += '<div class = "panel-body">'
				address_html += '<center>';
				address_html += '<input type="radio" name="address" id="address" value="0" onclick="fillNewaddress(this.value);"/>';
				address_html += '</center>';
				address_html += '</div>';
				address_html += '</div>';
				address_html += '</div>';
				$('.address_data').html(address_html);
			}
			alertify.alert(data.msg).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
		}
		SimpleLoading.stop();
    }).fail(function(data) {
		console.log('fail');
    });
	return false;
        
});
JS;
$this->registerJs($script1);



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