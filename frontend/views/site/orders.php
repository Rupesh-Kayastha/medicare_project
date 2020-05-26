<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'My Orders';
$this->params['breadcrumbs'][] = $this->title;


?>
<style>
	.ordersbox{background:#fff; height:auto;  border-radius:5px; padding:15px; margin-bottom:20px;box-shadow: 0px 15px 38px rgba(25, 25, 48, 0.1);}
	.orderhead{height:auto; float:left; padding-bottom:15px;}
	.orderid{border-radius:25px; height:auto; float:left;padding:10px; text-align:center; background:#efefef; color:#333;}
	.orderbottom{height:auto; float:left; padding-bottom:10px; padding-top:10px; border-top:1px solid #dedede;}
</style>
<div class="main-content">
	<div class="container cart-block-style">          
		<div class="contentText"></div>
		<h1>My Orders</h1>
		<?php 
		$i=0;
		foreach($order as $key=>$value)
		{
			foreach ($value->orderitems as $key => $valu) 
			{
				?>
				<div style="display: none;">
					<p>your Price is:<?=$value->OrderTotalPrice?><br> <br> your Payment Type is:
						<?php
						if($value->PaymentType==1)
						{
							$PaymentType="COD";
						}
						elseif($value->PaymentType==2)
						{
							$PaymentType="Online";
						}
						elseif($value->PaymentType==3)
						{
							$PaymentType="Direct";
						}
						elseif($value->PaymentType==4)
						{
							$PaymentType="EMI";
						}
						?>
						<br>your Order Type is:
						<?php
						if($value->OrderType==0)
						{
							$OrderType="Not Set";
						}
						elseif($value->OrderType==1)
						{
							$OrderType="Support";
						}
						elseif($value->OrderType==2)
						{
							$OrderType="Self";
						}
						?>
						<br>your Order Status is:
						<?php
						if($value->OrderStatus==0)
						{
							$OrderStatus="Open";
						}
						elseif($value->OrderStatus==1)
						{
							$OrderStatus="Confirm";
						}
						elseif($value->OrderStatus==2)
						{
							$OrderStatus="Reject";
						}
						?>
						<br>
					</p>
				</div>

				<div class="ordersbox col-md-12">
					<div class="orderhead col-md-12" >
						<div class="orderid col-md-4" style="padding-left:0px;">
							Order Id <span style="color:#0066CC;"><?=$value->OrderIdentifier?></span>
						</div>
						<div class="col-md-4"><span style="float:left; margin-top:10px;">Order Date And Time: <?=$value->CreatedDate?></span></div>
					</div>
					<div class="col-md-12" style="border-top:1px solid #dedede; padding-top:15px; padding-bottom:15px;">
						<div class="col-md-2">
							<img src="<?=$valu->orderMedicine->MediceneImage?>" height="120"/>
						</div>
						<div class="col-md-4">
							<h4><?=$valu->OrderItemName?></h4> 
							<h5>Qty : <?=$valu->OrderItemQty?></h5>
							<p>Order By : <?=$OrderType?></p>
							<p>Payment Type : <?=$PaymentType?></p>
						</div>
						<div class="col-md-3">
							<h4 style=" font-size:18px; color:#222;  padding-top:20px; margin-left:50px; line-height:1.3;"><span style="font-size:11px; color:#666666;">Price</span><br/>Rs.<?=$valu->OrderItemPrice?></h4>
						</div>
						<div class="col-md-3">
							<h4 style="color:#009900; font-size:18px;  padding-top:20px; margin-left:50px; line-height:1.3;"><span style="font-size:11px; color:#666666;">Status</span><br/><?=$OrderStatus?></h4>
						</div>
						<!-- <div class="col-md-3">
							<h4 style=" font-size:18px;  padding-top:20px; margin-left:50px; line-height:1.3;"><span style=" float:left;margin-top:10px;"><i class="fa fa-close" style="color:#FF3300;"></i> Cancel Order</span></h4>
						</div> -->
					</div>
					

					<div class="orderbottom col-md-12" >
						<div class="col-md-3 col-md-offset-4">
							<h3 style="margin-top:10px; margin-bottom:0px; font-size:18px; padding:0px; text-align:right; color:#111;">Total Price : Rs.<?=$valu->OrderItemTotalPrice?></h3>
						</div>
					</div>
				</div>
				<?php
				$i++;
			}
		}
		?>
	</div>
</div>