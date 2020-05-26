<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\tabs\TabsX;
use fedemotta\datatables\DataTables;
$this->title = "My EMI's";
$this->params['breadcrumbs'][] = $this->title;

if($emi['status']){
$cm_deatis='';
foreach($emi['data']['EMI']['Current']['Schedules'] as $order){
   
$cm_deatis.='<tr><td>'.$order['OrderIdentifier'].'</td>'.$order['Amount'].'<td></td>';
}
$total=$emi['data']['EMI']['Current']['Total'];
$month=$emi['data']['EMI']['Current']['Month'];



$CM=<<<CM

<div class="table-responsive margin-top">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">Order Id</th>
                <th class="text-left">Emi Amount</th>						
            </tr>
        </thead>
        <tbody>
         $cm_deatis
        </tbody>
        <tfoot>
            <tr><th>Total amount for the month of $month</th><th>$total</th></tr>
        </tfoot>
        
    </table>
</div>
CM;

$order_deatails='';
foreach($emi['data']['Orders'] as $orders){
 $emd='';  
 foreach($orders['EMI'] as $em){
     
     $emd.='<tr><td>'.$em['Month'].'</td><td>'.$em['Amount'].'</td>';
 }   
 
$emi_details='
<div class="table-responsive margin-top">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th clss="text-center">Month</th>
                <th class="text-left">Emi Amount</th>						
            </tr>
        </thead>
        <tbody>'.
         $emd
        .'</tbody>
    </table>
</div>';




$order_deatails.='<table class="table table-bordered"><thead><tr><th class="text-center">'.$orders["OrderIdentifier"].'</th></tr></thead><tbody><tr><td>'.$emi_details.'</td></tr></tbody></table>';
}

$All=<<<All

<div class="table-responsive margin-top">
 $order_deatails   
</div>
All;
    
    
    
}


    $items = [
        [
            'label'=>'Current Month',
            'content'=>$emi['status']==0?$emi['message']:$CM,
            'active'=>true
        ],
        [
            'label'=>'All Schedules',
            'content'=>$emi['status']==0?$emi['message']:$All,
            
        ]
    ];
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
		<h1>My EMI's</h1>
		<?php
        echo TabsX::widget([
            'items'=>$items,
            'position'=>TabsX::POS_ABOVE,
            'encodeLabels'=>false
        ]);
            
        ?>
	</div>
</div>