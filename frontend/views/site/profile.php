<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'My Profile';
$this->params['breadcrumbs'][] = $this->title;


?>
<style>
	.ordersbox{background:#fff; height:auto;  border-radius:5px; padding:15px; margin-bottom:20px;box-shadow: 0px 15px 38px rgba(25, 25, 48, 0.1);}
	.orderhead{height:auto;  padding-bottom:15px;}
	.orderid{border-radius:25px; height:auto; float:left;padding:10px; text-align:center; background:#efefef; color:#333;}
	.orderbottom{height:auto; float:left; padding-bottom:10px; padding-top:10px; border-top:1px solid #dedede;}
</style>
<div class="main-content">
    <div class="container cart-block-style">          
		<div class="contentText col-md-8 col-md-offset-2">
			<h1>My Profile</h1>
		</div>

<?php 
	$i=0;
	foreach($profile as $key=>$value)
	{
		?>

		<div class="ordersbox col-md-8 col-md-offset-2">
			<div class="orderhead col-md-12" style="padding-left:0px; padding-right:0px;">
				<div class="orderid col-md-4" style="padding-left:0px; ">
					 <span style="color:#0066CC;"><i class="fa fa-user-circle-o"></i> <?=$value->EmployeeName?></span>
				</div>
				<div class="col-md-4"></div>
				<div class="col-md-4" style="padding-right:0px;  text-align:right;"><i class="fa fa-phone"></i> Contact No : <?=$value->ContactNo?></div>
			</div>
			<div class="col-md-12" style="border-top:1px solid #dedede; padding:0px; padding-top:15px; padding-bottom:15px;">
				<div class="col-md-4">
					<h4 style=" font-size:18px; color:#222;  padding-top:10px; padding-bottom:10px; margin-left:50px; line-height:1.3; background:#efefef; border-radius:4px; text-align:center;"><span style="font-size:14px; color:#666666;">Credit Limit</span><br/><?=$value->CreditLimit?></h4> 
				</div>
				<div class="col-md-4">
					<h4 style=" color:#009900;font-size:18px;  padding-top:10px; padding-bottom:10px; margin-left:50px; line-height:1.3;  background:#efefef; border-radius:4px; text-align:center;"><span style="font-size:14px; color:#666666;">Credit Balance</span><br/><?=$value->CreditBalance?></h4>
				</div>
				<div class="col-md-4">
					<h4 style="color:#FF9900; font-size:18px;  padding-top:10px; padding-bottom:10px; margin-left:50px; line-height:1.3;  background:#efefef; border-radius:4px; text-align:center;"><span style="font-size:14px; color:#666666;">Credit On Hold</span><br/><?=$value->CreditOnHold?></h4>
				</div>
			</div>
			
		</div>
		

<?php
		$i++;
	}
	?>
</div>
</div>

