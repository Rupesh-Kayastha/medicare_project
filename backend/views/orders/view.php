<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;
use yii\helpers\Url;

use edwinhaq\simpleloading\SimpleLoading;
SimpleLoading::widget();


/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = Yii::t('backend', '{modelClass}', [
    'modelClass' => 'Order',
]) . ' #' . $model->OrderIdentifier;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Orders'), 'url' => [$ref]];
$this->params['breadcrumbs'][] = $this->title;
$order_type=array(0=>'Not Set', 1=>'Order By Support',2=>'Order By Self');
$payment_type=array(1=>'COD',2=>'Online',3=>'Direct',4=>'EMI');
?>

<body>
<div>
<div class="row excel" id="aprint">
    <div class="col-md-12">
    	<a href="#" class="btn btn-primary" style="float:right!important;" id="btnprint" onclick="printContent('aprint')">print</a>
    	<div id="editor"></div>
    	<button  class="btn-success" id="cmd"><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i> </button>
    	<div id="pdf">
        <?php         Panel::begin(
        [
        'header' => Html::encode($this->title),
        'icon' => 'users',
        ]
        )
		
         ?> 
         
        <div class="orders-view">
		<?php 
		if($model->OrderStatus == 0){
		?>
		<div class="row" >
			<div class="col-md-2 col-sm-2"><strong>ORDER ACTION:</strong> </div>
			<div class="col-md-10 col-sm-10">
				<input type="button" name="confirm" value="Confirm" id="confirm" class="btn btn-success" onclick="orderConfirm(<?=$model->OrderId?>);"/>
				<input type="button" name="reject" value="Reject" id="reject" class="btn btn-danger" onclick="orderReject(<?=$model->OrderId?>);"/>
			</div>
		</div>
		<?php 
		}
		?>
		<div class="row">
			<div class="form-group col-md-4 col-sm-4 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Order Details')),
								'icon' => 'users',
							]
						)
					?>
					<table class="table">
						<tbody>
							<tr>
								<th scope="row">Order ID</th>
								<td><?= $model->OrderIdentifier;?></td>
							</tr>
							<tr>
								<th scope="row">Name</th>
								<td><?= $model->employee->EmployeeName; ?></td>
							</tr>
							<tr>
								<th scope="row">Company</th>
								<td><?= $model->employee->company->Name; ?></td>
							</tr>
							<tr>
								<th scope="row">Employee ID</th>
								<td><?= $model->employee->EmpId; ?></td>
							</tr>
							<tr>
								<th scope="row">Email</th>
								<td><?= $model->employee->EmailId; ?></td>
							</tr>
							<tr>
								<th scope="row">Contact No</th>
								<td><?= $model->employee->ContactNo; ?></td>
							</tr>
							<tr>
								<th scope="row">Ordered As</th>
								<td><?= $order_type[$model->OrderType]; ?></td>
							</tr>							
						</tbody>
					</table>					
					<?php Panel::end() ?>
			</div>
			<div class="form-group col-md-4 col-sm-4 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Shipping Detils')),
								'icon' => 'users',
							]
						)
					?>
					<table class="table">
						<tbody>
							<tr>
								<th scope="row">Address Line 1</th>
								<td><?= $model->employeeaddress->AddressLine1;?></td>
							</tr>
							<tr>
								<th scope="row">Address Line 2</th>
								<td><?= $model->employeeaddress->AddressLine2;?></td>
							</tr>
							<tr>
								<th scope="row">Land Mark</th>
								<td><?= $model->employeeaddress->LandMark;?></td>
							</tr>
							<tr>
								<th scope="row">City</th>
								<td><?= $model->employeeaddress->City;?></td>
							</tr>
							<tr>
								<th scope="row">State</th>
								<td><?= $model->employeeaddress->State;?></td>
							</tr>
							<tr>
								<th scope="row">Pincode</th>
								<td><?= $model->employeeaddress->Zipcode;?></td>
							</tr>
							<tr>
								<th scope="row">Contact No</th>
								<td><?= $model->employeeaddress->ContactNo;?></td>
							</tr>
							
													
						</tbody>
					</table>					
					<?php Panel::end() ?>
			</div>
			<div class="form-group col-md-4 col-sm-4 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Payment Detils')),
								'icon' => 'users',
							]
						)
					?>
					<table class="table">
						<tbody>
							<tr>
								<th scope="row">Order Total</th>
								<td><?= $model->OrderTotalPrice; ?></td>
							</tr>
							<tr>
								<th scope="row">Payment Method</th>
								<td><?= $payment_type[$model->PaymentType];?></td>
							</tr>
							<tr>
								<th scope="row">Credit Used</th>
								<td><?= $model->CreditBalanceUsed;?></td>
							</tr>
							<tr>
								<th scope="row">EMI Plan</th>
								<td><?= $model->PaymentType==4? $model->emiplan->EmiPlanName: "N/A"; ?></td>
							</tr>
							<tr>
								<th scope="row">EMI Period In Months</th>
								<td><?= $model->PaymentType==4? $model->EmiPlanPeriod: "N/A"; ?></td>
							</tr>
							<tr>
								<th scope="row">EMI Amount</th>
								<td><?= $model->PaymentType==4? $model->EmiAmount: "N/A"; ?></td>
							</tr>						
						</tbody>
					</table>					
					<?php Panel::end() ?>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-12 col-sm-12 col-xs-12">
					<?php 
						Panel::begin(
							[
								'header' => Html::encode(Yii::t('backend', 'Order Items')),
								'icon' => 'users',
							]
						)
					?>
					

					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Qty</th>
								<th>Unit Price</th>
								<th>Total Price</th>
							</tr>
							
						</thead>
						<tbody>
							<?php
							$i=1;
							foreach($model->orderitems as $item){
							?>
							<tr>
								<th scope="row"><?= $i;?></th>
								<td><?= $item->OrderItemName;?></td>
								<td><?= $item->OrderItemQty;?></td>
								<td><?= $item->OrderItemPrice;?></td>
								<td><?= $item->OrderItemTotalPrice;?></td>
							</tr>
							<?php $i++; } ?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3">&nbsp;</th>
								<th>Grand Total</th>
								<th><?= $model->OrderTotalPrice;?></th>
							</tr>
							<?php if($model->OrderComment){ ?>
							<tr>
								<th scope="row" colspan="5">Order Comments</th>								
							</tr>
							<tr>
								<td scope="row" colspan="5"><?= $model->OrderComment;?></td>								
							</tr>
							<?php } ?>
						</tfoot>
					</table>


					
					
					
					<?php Panel::end() ?>
			</div>
		</div>
			
			
        </div>

        <?php Panel::end() ?> 
        </div>

    </div>
</div>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">
	function orderConfirm(OrderId){
		
		alertify.confirm('Are you sure want to confirm the order.....', function(){
			$.ajax({
				url: "<?= Url::to(['orders/order-confirm']);?>",
				type: "POST",
				data: {
					OrderId: OrderId,
				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('hourglass'); 
				},
				success: function (result) {
					if(result.status == 1){
						location.href = "<?= Url::to(['orders/view']);?>?id="+OrderId+"&ref=confirm-order";
					}
				},
				complete:function(json)
				{
					SimpleLoading.stop();
				},
			});
		}).set('labels', {ok:'Confirm', cancel:'Cancel'}).setHeader('<em class="alert_header"> Arunodayamedicare </em> ');
		
	
	}
	function orderReject(OrderId,datakey){
		
		alertify.confirm().setting({
			'message': '<label>Comment</label><textarea row="9" col="9" id="ordercomment" class="alert_textarea" placeholder="Please enter comment for reject"></textarea>',
			'onok': function(){
				$.ajax({
					url: "<?= Url::to(['orders/order-reject']);?>",
					type: "POST",
					data: {
						OrderId: OrderId,
						Comment: $('#ordercomment').val(),
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (result) {
						if(result.status == 1){
							location.href = "<?= Url::to(['orders/view']);?>?id="+OrderId+"&ref=reject-order";
						}
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
				});
				
			}
		}).set('labels', {ok:'Reject', cancel:'Cancel'}).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
	}
</script>
<script>
	function printContent(el)
	{
		var restorepage = document.body.innerHTML;
		var printcontent = document.getElementById(el).innerHTML;
		document.body.innerHTML = printcontent;
		$('#btnprint').hide();
		$('#cmd').hide();
		window.print();
		$('#btnprint').show();
		$('#cmd').show();
		location.reload();
		document.body.innerHTML = restorepage;
	}
</script>
<?php
$script = <<< JS
$("body").on("click", "#cmd", function () {
            html2canvas($('#pdf')[0], {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("Table.pdf");
                }
            });
        });
JS;
$this->registerJs($script);
?>


