<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use edwinhaq\simpleloading\SimpleLoading;
SimpleLoading::widget();

$this->title = 'Cart';

?>
<style>
.alert_header{
	color: #ce7a3e;
    font-size: 16px;
}
table tr th{background:#2a5b86;color:#fff; font-weight:normal;}
table tr td{vertical-align:middle !important;font-size: 16px;
color: #555;}
</style>
<div class="main-content">
    <div class="container cart-block-style">          
		
		<div class="contentText">
			<h1>Cart
			</h1>
		</div>
        <?php 
		if(count($CartItem['CartItems'])){
		?>
			<div class="table-responsive margin-top">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-center">PRODUCT</th>
							<th class="text-left">PRODUCT NAME</th>
							<th class="text-left">QUANTITY</th>
							<th class="text-right">UNIT R.PRICE</th>
							<th class="text-right">UNIT D.PRICE</th>
							<th class="text-right">TOTAL R.PRICE</th>
							<th class="text-right">TOTAL D.PRICE</th>
						</tr>
					</thead>
					<tbody>
						<!------------------------------  Dynamic Section  ------------------------------------->
						<?php
						
							foreach($CartItem['CartItems'] as $cartkey => $cartval){
						?>
						<tr id="medicine_<?=$cartval['CartMedicineId']?>">
							<td class="text-center">                                   
								<a href="single-product.html">
									<img title="ana"  src="<?=$cartval['CartItemImage']?>" style="width: 100px; height: 80px;">
								</a>
							</td>
							<td class="text-left">
								<a href="#">
									<?=$cartval['CartItemName']?>
								</a>
							</td>
							<td class="text-left">
								<div style="max-width: 180px; margin:auto;" class="input-group btn-block">
									<span class="input-group-btn">
										<button id="medicine_qty_dec_<?=$cartval['CartMedicineId']?>" class="btn btn-light" type="button" style="border-radius:4px;border: 1px solid #dedede;" onclick="cartQtyupdate(0,<?=$cartval['CartMedicineId']?>);"><i class="fa fa-minus"></i></button>
									</span>
									<input id="medicine_qty_<?=$cartval['CartMedicineId']?>" type="text" class="form-control input-sm" size="1" style="height:35px;box-shadow: none;width: 54px; margin:5px;" value="<?=$cartval['CartItemQty']?>" readonly>
									<span class="input-group-btn">
										<button id="medicine_qty_inc_<?=$cartval['CartMedicineId']?>" class="btn btn-light" type="button" style="border-radius:4px; border: 1px solid #dedede;" onclick="cartQtyupdate(1,<?=$cartval['CartMedicineId']?>);"><i class="fa fa-plus"></i></button>
										<button id="medicine_qty_rem_<?=$cartval['CartMedicineId']?>" class="btn btn-danger"  type="button" style="margin-left:5px; border-radius:3px;" onclick="cartQtyupdate(2,<?=$cartval['CartMedicineId']?>);"><i class="fa fa-times-circle"></i></button>
									</span>
								</div>
							</td>
							<td class="text-right" id="medicine_rp_<?=$cartval['CartMedicineId']?>">
								&#8377;<?=number_format($cartval['CartItemRegularPrice'],2)?>
							</td>
							<td class="text-right" id="medicine_dp_<?=$cartval['CartMedicineId']?>">
								&#8377;<?=number_format($cartval['CartItemDiscountedPrice'],2)?>
							</td>
							<td class="text-right" id="medicine_rtp_<?=$cartval['CartMedicineId']?>">
								&#8377;<?=number_format($cartval['CartItemRegularRowTotal'],2)?>
							</td>
							<td class="text-right" id="medicine_dtp_<?=$cartval['CartMedicineId']?>">
								&#8377;<?=number_format($cartval['CartItemDiscountedRowTotal'],2)?>
							</td>
						</tr>
						<?php 
							}
						
						?>
						<!------------------------------ End Dynamic Section--------------------------------->
					</tbody>
				</table>
			</div>
        
        
		<div class="row">
			<div class="col-sm-4 col-sm-offset-8">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<span style="font-size: 20px;float: right; margin-bottom:10px;">Pricing Details</span>
							<td class="text-right">
								Total Regular Price:
							</td>
							<td class="text-right" id="CartRegularTotalPrice">
								<span style="font-weight:bold; font-size:22px;">&#8377;<?=number_format($CartItem['CartDetails']['RegularTotalPrice'],2)?></span>
							</td>
						</tr>
						<tr>
							<td class="text-right">
								Total Discounted Price:
							</td>
							<td class="text-right" id="CartDiscountedTotalPrice">
								<span style="color:#FF6600;">&#8377;<?=number_format($CartItem['CartDetails']['DiscountedTotalPrice'],2)?></span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="buttons">
			<div class="pull-left"><a class="btn btn-info" href="<?= Url::to(Yii::$app->request->referrer);?>"><i class="fa fa-caret-right"></i>&nbsp;Continue Shopping</a></div>
			<div class="pull-right"><a class="btn btn-primary reg_button" href="<?= Url::to(['cart/checkout']);?>">Proceed to Checkout</a></div>
		</div>
		<?php 
		}else{
		?>
			<div class="row">
			<p>Your shopping cart is empty...</p>
			
			</div>
			<div class="buttons">
				<div class="pull-left">
					<a class="btn btn-default" href="<?= Url::to(Yii::$app->request->referrer);?>">
						<i class="fa fa-caret-right"></i>&nbsp;Continue Shopping
					</a>
				</div>
			
		    </div>
		<?php
		}
		?>
		
    </div>
</div>
<?php
$script=<<<JS

$('#search_manu').hide();
JS;
$this->registerJs($script);
?>
<script type="text/javascript">


function cartQtyupdate(type,medicineid){
	
		if(type == 1){
			$.ajax({
					url: "<?= Url::to(['cart/additem']);?>",
					type: "POST",
					data: {
						medicineid:medicineid,
						ordertype:2
						//_csrf : '<?=Yii::$app->request->getCsrfToken()?>',
						//ticket_token:ticket_token,						
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (result) {
						//console.log(result);
						if(result.status == 1){
							//alert(123);
						var RegularTotalPrice = '&#8377;'+convertIndiancurrency(result.cart_updatedata.RegularTotalPrice);
						var DiscountedTotalPrice = '&#8377;'+convertIndiancurrency(result.cart_updatedata.DiscountedTotalPrice);
						var CartItemRegularRowTotal = '&#8377;'+convertIndiancurrency(result.cart_updatedata.CartItemRegularRowTotal);
						var CartItemDiscountedRowTotal = '&#8377;'+convertIndiancurrency(result.cart_updatedata.CartItemDiscountedRowTotal);
				
							$('#CartRegularTotalPrice').html(RegularTotalPrice);
							$('#CartDiscountedTotalPrice').html(DiscountedTotalPrice);
							$('#medicine_qty_'+medicineid).val(result.cart_updatedata.CartItemQty);
							$('#medicine_rtp_'+medicineid).html(CartItemRegularRowTotal);
							$('#medicine_dtp_'+medicineid).html(CartItemDiscountedRowTotal);
							
							
							/*********** Header part Update ***********/
							var Totalamount = convertIndiancurrency(result.Headercartdetails.Totalamount);
							var TotalItems = '('+result.Headercartdetails.Totalcartitem+')';
							$('#cart_total').html(Totalamount);
							$('.cart_items').html(TotalItems);
							
							alertify.alert('Product quantity updated sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						}
						
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
		}else if(type == 0){
			$.ajax({
					url: "<?= Url::to(['cart/updatequantity']);?>",
					type: "POST",
					data: {
						medicineid:medicineid,
						ordertype:2
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (result) {
						if(result.status == 1){
							
							if(result.CheckItem == 0){
								$('#medicine_'+medicineid).remove();
								alertify.alert('Product remove sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
							}else{
								alertify.alert('Product quantity updated sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
							}
							
							var RegularTotalPrice = '&#8377;'+convertIndiancurrency(result.cart_updatedata.RegularTotalPrice);
							var DiscountedTotalPrice = '&#8377;'+convertIndiancurrency(result.cart_updatedata.DiscountedTotalPrice);
							$('#CartRegularTotalPrice').html(RegularTotalPrice);
							$('#CartDiscountedTotalPrice').html(DiscountedTotalPrice);
							$('#medicine_qty_'+medicineid).val(result.cart_updatedata.CartItemQty);
							
							/*********** Header part Update ***********/
							var Totalamount = convertIndiancurrency(result.Headercartdetails.Totalamount);
							var TotalItems = '('+result.Headercartdetails.Totalcartitem+')';
							$('#cart_total').html(Totalamount);
							$('.cart_items').html(TotalItems);
							
							
						}else{
							alertify.alert('Product quantity not updated').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						}

					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
			});
		}else if(type==2){
			$.ajax({
					url: "<?= Url::to(['cart/remove-item']);?>",
					type: "POST",
					data: {
						medicineid:medicineid,
						ordertype:2
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('hourglass'); 
					},
					success: function (result) {
						var RegularTotalPrice = '&#8377;'+convertIndiancurrency(result.cart_updatedata.RegularTotalPrice);
						var DiscountedTotalPrice = '&#8377;'+convertIndiancurrency(result.cart_updatedata.DiscountedTotalPrice);
						$('#CartRegularTotalPrice').html(RegularTotalPrice);
						$('#CartDiscountedTotalPrice').html(DiscountedTotalPrice);
						$('#medicine_'+medicineid).remove();
						/*********** Header part Update ***********/
						var Totalamount = convertIndiancurrency(result.Headercartdetails.Totalamount);
						var TotalItems = '('+result.Headercartdetails.Totalcartitem+')';
						$('#cart_total').html(Totalamount);
						$('.cart_items').html(TotalItems);
						
						alertify.alert('Product remove sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
						
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
</script>