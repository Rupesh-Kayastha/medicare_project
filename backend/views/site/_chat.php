<?php 
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use edwinhaq\simpleloading\SimpleLoading;
SimpleLoading::widget();


$medicine_data = \common\models\Medicine::find()
    ->select(['Name as value', 'Name as  label', 'MedicineId as id'])
    ->asArray()
    ->all();
?>
<style>
#cartData .qty{
	padding:10px;
}
#cartData td{
	
}
#cart_box_emp{
	/*height: 12vh;*/
}
#cart_box_search{
	/*height: 7vh;*/
	/*padding: 5px;*/
}
#cart_box_cartdetails{
	/*height: 45vh;*/
    /*overflow-y: scroll;*/
}
#cart_box_operation{
	/*height: 14vh;*/
}
.pointer{
	cursor: pointer;
}
#myModal {
  position: relative;
}

.modal-dialog {
  position: fixed;
  width: 60%;
  margin-left: 20%;
  padding: 10px;
}
.box-address{
	border:1px solid #232323;
	padding:5px;
	
}
.alert_header{
	color: #ce7a3e;
    font-size: 16px;
}
.emi_label{
	
}
</style>

	<div class="col-md-3 col-xs-3 box-ticket-container box" id="alltoken">
    <?php
    if($allticket)
    // 	var_dump($allticket);
    // die();
    {
        foreach($allticket as $akey=>$aval)
        {
    ?>
    <div  class="col-md-12 col-xs-12 ticket-box pointer" onclick="tickethandle(<?=$aval->TicketID;?>,'<?=$aval->Token;?>');">
        Ticket - <?=$aval->Token;?><br/>
        Employee Name - <?=$aval->user->EmployeeName;?><br/>
        Time - <?=date('d M Y H:i:s',strtotime($aval->OnDate));?><br/>
    </div>
    <?php
        }
    }
    ?>
    </div>
	<div class="col-md-4 col-xs-4 box-ticket-container box">
	<div id="chatbox"></div>
	</div>
	<div class="col-md-5 col-xs-5 box-ticket-container box">
		<div class="row" id="operator_cart" style="display:none;">
			<div class="col-md-12 col-xs-12 box-ticket-order" id="cart_box_emp">
				<div class="col-md-12 col-xs-12"><center>Employee Details</center></div>
				<div class="col-md-6 col-xs-6">
					<div class="col-md-12 col-xs-12"> Name 		: <span id="EmployeeName"></span></div>
					<div class="col-md-12 col-xs-12"> Company	: <span id="CompanyName"></span></div>
					<div class="col-md-12 col-xs-12"> ID 		: <span id="EmpId"></span></div>
				</div>
				<div class="col-md-6 col-xs-6">
					<div class="col-md-12 col-xs-12"> Max. Credit Limit : <span id="CreditLimit"></span></div>
					<div class="col-md-12 col-xs-12"> Credit Balance  : <span id="CreditBalance"></span></div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 box-ticket-order" id="cart_box_search">
				<div class="col-md-2 col-xs-2">Search :</div>
				<div class="col-md-8 col-xs-8">
					<?php $form = ActiveForm::begin(); ?>
					<?= AutoComplete::widget([
						'name' => 'medicine_name',
						'id' => 'medicine_name',
						'value' => '',
						'clientOptions' => [
							'source' => $medicine_data,
							'autoFill' => true,
							'minLength' => '1',
							'select' => new JsExpression("function( event, ui ) {
					$('#medicine_id').val(ui.item.id);
					$('#med_add_btn').prop('disabled', false);
					}")],
						'options' => [
							'class' => 'form-control'
						]
					]);
					?>
					<?= Html::hiddenInput('medicine_id', '',['id'=>'medicine_id']); ?>
					<?= Html::hiddenInput('ticket_token', '',['id'=>'ticket_token']); ?>
					<?= Html::hiddenInput('EmployeeId', '',['id'=>'EmployeeId']); ?>

					<?php ActiveForm::end(); ?>	
					
				</div>
				<div class="col-md-2 col-xs-2">
					<?= Html::button('Add',['class'=>'btn btn-primary',"disabled"=>"disabled","id"=>"med_add_btn",'onClick'=>'productAdd()']) ?>
				</div>
			</div>
			<!------------------Cart Details -------------------->
			<div class="col-md-12 col-xs-12 box-ticket-order" id="cart_box_cartdetails">
				<table class="table">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="30%">Name</th>
							<th width="20%">QTY</th>
							<th width="10%">Price(R)</th>
							<th width="10%">Price(D)</th>
							<th width="10%">Total Price(R)</th>
							<th width="10%">Total Price(D)</th>
							<th width="5%">&nbsp;</th>
						</tr>
					</thead>
					<tbody id="cartData"></tbody>
					<tfoot>
						<tr>
							<td colspan="2">Cart Total Regular Amount </td>
							<td colspan="2" id="ctra">&nbsp;</td>
							<td colspan="2">Cart Total Discounted Amount </td>
							<td colspan="2" id="ctda">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2">Payment Details </td>
							<td colspan="2" id="payment_deatils"></td>
							<td colspan="4" ><div id="emi_details" style="display:none;"></div></td>
						</tr>
						<tr>
							<td colspan="2">Address  </td>
							<td colspan="6" id="employee_address">&nbsp;</td>
						</tr>
						
						
					</tfoot>
				</table>
			</div>
			<!-----------------End------------------>
			<div class="col-md-12 col-xs-12 box-ticket-order" id="cart_box_operation" style="display:none">
				<div class="col-md-6 col-xs-6">
					<div class="col-md-12 col-xs-12">
						Set Payment Details:
					</div>
					<div class="col-md-12 col-xs-12">
						<?= Html::dropDownList('PaymentType', null,['1' => 'COD(Self)', '2' => 'Online(Self)', '3' => 'Direct','4' => 'EMI'],['prompt'=>'Select...','id'=>'PaymentType','class'=>'form-control','onChange'=>'choosePaymenttype(this.value);']) ?>
					</div>
					<div class="col-md-12 col-xs-12" id="emiplandetails" style="display:none;"></div>
				</div>
				<div class="col-md-6 col-xs-6">
					<div class="col-md-12 col-xs-12">
					Set Address Details: 
					</div>
					<div class="col-md-12 col-xs-12">
					<?= Html::button('Set Address',['class'=>'btn btn-primary','id'=>'set_user_address','data-toggle'=>'modal','onClick'=>'getAddress()']) ?>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-xs-12 box-ticket-order generate_link" style="display:none;">
				<div class="col-md-4 col-xs-4"></div>
				<div class="col-md-4 col-xs-4">
					<input type="button" name="generatelink" value="Generate Link" class="btn btn-primary" onclick="generateLink();"/>
				</div>
				<div class="col-md-4 col-xs-4"></div>
			</div>
		</div>
	</div>
	
	<!-- Modal -->
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Address Details</h4>
				</div>
				<div class="modal-body">
					<div class="row" id="user_address">
					
					
					
					
					</div>
					<div class="row" id="create_employee_address" style="display:none;">
						<form id="address_create" name="address_create" method="post" action="<?=Url::to(['ticket-order/createemployeeaddress'])?>">
							<div class="col-md-12 col-xs-12">
							<center><h4 class="modal-title">Create Address</h4></center>
							</div>
							<div class="form-group col-md-6 col-xs-6">
								<label for="addressline1">Address Line-1:</label>
								<input type="text" class="form-control" id="addressline1" name="addressline1" required>
								<input type="hidden" class="form-control" id="employee_ticket_token" name="ticket_token" required>
							</div>
							<div class="form-group col-md-6 col-xs-6">
								<label for="landmark">Landmark:</label>
								<input type="text" class="form-control" id="landmark" name="landmark" required>
							</div>
							<div class="form-group col-md-6 col-xs-6">
								<label for="addressline2">Address Line-2:</label>
								<input type="text" class="form-control" id="addressline2" name="addressline2" required>
							</div>
							<div class="form-group col-md-6 col-xs-6">
								<label for="state">State:</label>
								<input type="text" class="form-control" id="state" name="state" required>
							</div>
							<div class="form-group col-md-6 col-xs-6">
								<label for="city">City:</label>
								<input type="text" class="form-control" id="city" name="city" required>
							</div>
							<div class="form-group col-md-6 col-xs-6">
								<label for="zipcode">Zipcode:</label>
								<input type="text" class="form-control" id="zipcode" name="zipcode" required>
							</div>
							<div class="form-group col-md-6 col-xs-6">
								<label for="contactno">Contact No.:</label>
								<input type="text" class="form-control" id="contactno" name="contactno" required>
							</div>
							<div class="form-group col-md-12 col-xs-12">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	
	
	
	<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" style="overflow: initial !important;">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title" id="myModalLabel">Prescription Preview</h4>
		  </div>
		  <div class="modal-body" style="max-height: calc(100vh - 200px); overflow: auto;">
			<center>
			<img src="" id="imagepreview" style="width:90%;padding:5%;">
			</center>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>

	
	
 <?php
$script=<<<JS

$('#medicine_name').on('input',function () {
	$('#medicine_id').val('');
	$('#med_add_btn').prop("disabled", true);
                       
});
JS;
$this->registerJs($script);
?>
<script type="text/javascript">
	
	function viewPresscription(ele){
		$('#imagepreview').attr('src', $(ele).data('src'));
		$('#imagemodal').modal('show');
	}
	function productAdd(){
		
		var medicine_id = $('#medicine_id').val();
		var ticket_token = $('#ticket_token').val();
		//console.log(ticket_token);
		if(medicine_id!='' && ticket_token!=''){
			$.ajax({
					url: "<?= Url::to(['ticket-order/productadd']);?>",
					type: "POST",
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					data: {
						_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
						medicineid:medicine_id,
						ticket_token:ticket_token,
						ordertype:1
					},
					success: function (data) {
						alertify.alert('Product added sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						viewCart(ticket_token);
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
		}else{
			console.log('MedicineID and TicketToken are required...');
			return false;
		}
	}
	function cartUpdate(type,medicineid,ticket_token){
		if(type == 1){
			$.ajax({
					url: "<?= Url::to(['ticket-order/productadd']);?>",
					type: "POST",
					data: {
						_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
						medicineid:medicineid,
						ticket_token:ticket_token,
						ordertype:1
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (data) {
						alertify.alert('Product added sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						viewCart(ticket_token);
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
		}else if(type == 0){
			$.ajax({
					url: "<?= Url::to(['ticket-order/updatequantity']);?>",
					type: "POST",
					data: {
						_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
						medicineid:medicineid,
						ticket_token:ticket_token,
						ordertype:1
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (data) {
						console.log(data);
						if(data==1){
							alertify.alert('Product remove sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						}else{
							alertify.alert('Product quantity updated sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						}
						viewCart(ticket_token);
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
		}else if(type==2){
			$.ajax({
					url: "<?= Url::to(['ticket-order/remove-item']);?>",
					type: "POST",
					data: {
						_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
						medicineid:medicineid,
						ticket_token:ticket_token,
						ordertype:1
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (data) {
						alertify.alert('Product remove sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						viewCart(ticket_token);
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
			
		}else{
			console.log("Error: cartUpdate()->error");
		}
	}
	
	function choosePaymenttype(paymenttype){
		var ticket_token = $('#ticket_token').val();
		if(ticket_token!='' && paymenttype!=''){
			if(paymenttype!=4){
				$.ajax({
						url: "<?= Url::to(['ticket-order/setpayment']);?>",
						type: "POST",
						data: {
							_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
							ticket_token:ticket_token,
							PaymentType:paymenttype,
						},
						beforeSend:function(json)
						{ 
							SimpleLoading.start('hourglass'); 
						},
						success: function (data) {
							alertify.alert('Payment update sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
							viewCart(ticket_token);
						},
						complete:function(json)
						{
							SimpleLoading.stop();
						},
				});
			}else{
				$.ajax({
						url: "<?= Url::to(['ticket-order/emipayment']);?>",
						type: "POST",
						data: {
							_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
							ticket_token:ticket_token,
							PaymentType:paymenttype,
						},
						beforeSend:function(json)
						{ 
							SimpleLoading.start('hourglass'); 
						},
						success: function (data) {
							$('#emiplandetails').html('');
							if(data.status == 1){
								//success code write here..
								$('#emiplandetails').show();
								var emipalndata = data.emidata;
								var emiplan = "";
								
								//console.log(emipalndata+'===');
								$.each(emipalndata,function(i,it){
									var radiovalidate = '';
									if(it.Status == 0){
										radiovalidate = 'disabled';
									}else{
										radiovalidate = '';
									}
									emiplan += "<div class='col-md-6 col-xs-6'>";
							        emiplan += "<input type='radio' name='company_emi' "+radiovalidate+" value='"+it.EmiPlanId+"' onchange='addEmidetails()'/>";
									emiplan += "<label class='emi_label'>"+it.EmiPlanName+"</label>";
						            emiplan += "</div>";
								});
								
								$('#emiplandetails').html(emiplan);
								//viewCart(ticket_token);
							}else{
								alertify.alert(data.msg).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
								viewCart(ticket_token);
							}
							
						},
						complete:function(json)
						{
							SimpleLoading.stop();
						},
				});
			}
		}
	}
	
	function getAddress(){
		var ticket_token = $('#ticket_token').val();
		$('#employee_ticket_token').val(ticket_token);
		if(ticket_token!=''){
			//$('#myModal').modal('show');
			$.ajax({
					url: "<?= Url::to(['ticket-order/getuseraddress']);?>",
					type: "POST",
					data: {
						_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
						ticket_token:ticket_token,
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (data) {
						var htmldata = "";
						//console.log(data);
						if(data.addressdata.length > 0){
							$.each(data.addressdata, function(i,it){
								htmldata += "<div class='col-md-3 col-xs-3'>";
								htmldata += "<div class='box-address'>";
								htmldata += "<div class='box-address-body'>";
								htmldata += "<div class='box-address-body-text'>"+it.AddressLine1+"</div>";
								htmldata += "<div class='box-address-body-text'>"+it.AddressLine2+"</div>";
								htmldata += "<div class='box-address-body-text'>City: "+it.City+"</div>";
								htmldata += "<div class='box-address-body-text'>State: "+it.State+"</div>";
								htmldata += "<div class='box-address-body-text'>L.M.: "+it.LandMark+"</div>";
								htmldata += "<div class='box-address-body-text'>Zip: "+it.Zipcode+"</div>";
								htmldata += "<div class='box-address-body-text'>MOB: "+it.ContactNo+"</div>";
								htmldata += "<div class='box-address-body-text'>";
								htmldata += "<input type='radio' name='address' value='"+it.EmployeeAddressId+"' onchange='checkRadioaddress()'/>";
								htmldata += "</div>";
								htmldata += "</div>";
								htmldata += "</div>";
								htmldata += "</div>";
							})
						}
						htmldata += "<div class='col-md-3 col-xs-3'>";
						htmldata += "<div class='box-address'>";
						htmldata += "<div class='box-address-body'>";
						htmldata += "<h5 class='box-address-title'>Add New Address</h5>";
						htmldata += "<div class='box-address-body-text'>";
						htmldata += "<input type='radio' name='address' value='0' onchange='checkRadioaddress()'/>";
						htmldata += "</div>";
						htmldata += "</div>";
						htmldata += "</div>";
						htmldata += "</div>";
						
						
						$('#user_address').html(htmldata);
						$('#myModal').modal('show');
						if (!($('.modal.in').length)) {
							$('.modal-dialog').css({
							  top: 0,
							  left: 0
							});
						}
						$('#myModal').modal({
							backdrop: false,
							show: true
						});

						$('.modal-dialog').draggable({
							handle: ".modal-header"
						});
						//viewCart(ticket_token);
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
		}else{
			console.log('Error: Ticket Token Not Available in getAddress()...');
		}
	}
	function checkRadioaddress(){
		var ticket_token = $('#ticket_token').val();
		if($("input:radio[name='address']").is(":checked")) {
			var radioval = $("input[name='address']:checked").val();
			if(radioval==0){
				$('#create_employee_address').show();
			}else{
				$('#create_employee_address').hide();
				$.ajax({
					url: "<?= Url::to(['ticket-order/setemployeeaddress']);?>",
					type: "POST",
					data: {
						_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
						ticket_token: $('#ticket_token').val(),
						addressid: radioval,
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (data) {
						if(data.status == 1){
							alertify.alert(data.msg);
							$('#myModal').modal('hide');
							viewCart(ticket_token);
						}else{
							alertify.alert(data.msg).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
							return false;
						}
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
				});
				
			}
		}
	}
	function addEmidetails(){
		if($("input:radio[name='company_emi']").is(":checked")) {
			var emiplanid = $("input[name='company_emi']:checked").val();
			var ticket_token = $('#ticket_token').val()
			$.ajax({
				url: "<?= Url::to(['ticket-order/updateemidata']);?>",
				type: "POST",
				data: {
					_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
					ticket_token: ticket_token,
					emimethod:4,
					emiplanid: emiplanid,
				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
				success: function (data) {
					if(data.status == 1){
						alertify.alert('EMI Payment type update successfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						viewCart(ticket_token);
					}
				},
				complete:function(json)
				{
					SimpleLoading.stop();
				},
			});
		}
	}
	function generateLink(){
		console.log('inside');
		var ticket_token = $('#ticket_token').val();
		$.ajax({
				url: "<?= Url::to(['ticket-order/linkgenerate']);?>",
				type: "POST",
				data: {
					ticket_token: ticket_token,
				},
				success: function (result) {
					if(result.status == 1){
						$("#chat_input"+ticket_token).val(result.data.link);
						var e = $.Event( "keypress", { which: 13 } );
						$("#chat_input"+ticket_token).trigger(e);
					}else{
						console.log('error');
					}
				},
		});
	}
	
	
	
</script> 

<?php
$script1=<<<JS
//alertify.alert('EMI Payment type update successfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
$('#address_create').submit(function(e){
	e.preventDefault(); 
	e.stopImmediatePropagation();
	var form = $(this);
	SimpleLoading.start('hourglass');
	$.ajax({
        type: 'POST',
		url: $(this).attr( 'action' ),
		data: form.serialize(),
    }).done(function(data) {
		if(data.status == 1){
			alertify.alert(data.msg).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
			//viewCart(ticket_token);
			getAddress();
			$('#create_employee_address').hide();
			
		}else{
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
?>