<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Cart View';
?>
<style type="text/css">
.tbl_caption{
    font-size: 25px;
    color: #8391a6;
}
</style>
	
<div class="view-cart">
	<?php $form = ActiveForm::begin(['action' => ['site/orderconfirm'],'options' => ['method' => 'post']]) ?>
	<table class="table table-bordered">
		<caption class="tbl_caption"><center>CART DEATILS</center></caption>
		<tbody>
			<?php 
			if($EmployeeAddress!=""){
			?>
			<tr>
				<th>Employee Name : </th>
				<td>
					<?=$EmployeeAddress['EmployeeName'];?>
					<input type="hidden" id="crt_ident" name="Order[CartIdentifire]" value="<?=$CartIdentifire?>" />
				</td>
			</tr>
			<tr>
				<th>Employee Contact No : </th>
				<td><?=$EmployeeAddress['ContactNo'];?></td>
			</tr>
			<tr>
				<th>Employee Address : </th>
				<td><?=$EmployeeAddress['AddressLine1']."<br/>".$EmployeeAddress['AddressLine2']."<br/>".$EmployeeAddress['LandMark']."<br/>".$EmployeeAddress['State']."<br/>".$EmployeeAddress['City']."<br/>".$EmployeeAddress['Zipcode'];?></td>
			</tr>
			<?php 
			}
			?>
		</tbody>
	</table>
	<table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Product Name</th>
        <th>QTY</th>
        <th>Total Price</th>
      </tr>
    </thead>
    <tbody>
	  <?php
	  if($CartItems!=""){
		  foreach($CartItems as $key=>$value){
			  if($CartDetails!=""){
				  if($CartDetails['PaymentMethods']!=4){
					  $itemtotalprice = $value->CartItemDiscountedRowTotal;
				  }else{
					  $itemtotalprice = $value->CartItemRegularRowTotal;
				  }
			  }
	  ?>
      <tr>
        <td><?=($key+1)?></td>
        <td><?=$value->CartItemName;?></td>
        <td><?=$value->CartItemQty;?></td>
        <td><?=$itemtotalprice;?></td>
      </tr>
	  <?php
		  }	  
	  }
	  if($CartDetails!=""){
		  if($CartDetails['PaymentMethods']!=4){
			  $totalcartprice = $CartDetails['DiscountedTotalPrice'];
		  }else{
			  $totalcartprice = $CartDetails['CartRegularPrice'];
		  }
	  ?>
	  <tr>
		<td colspan="3" style="text-align:right;">Total Cart Price :</td>
		<td colspan="2">Rs. <?=number_format($totalcartprice,2)?></td>
	  </tr>
	  <tr>
		<td colspan="3" style="text-align:right;">Payment Method :</td>
		<td colspan="2"><?=$CartDetails['PaymentMethod']?></td>
	  </tr>
	  
	  <?php 
	  }
	  ?>
	  <tr>
        <td colspan="4">
			
			<input type="submit" class="btn btn-primary" id="orderconfirm" value="<?=$btnname?>" <?=$btnstatus;?>/>
		</td>
	  </tr>
    </tbody>
  </table>
 <?php ActiveForm::end(); ?>
</div>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
         <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
         <h4><i class="icon fa fa-check"></i>Order Confirm!</h4>
         <?= Yii::$app->session->getFlash('success') ?>
    </div>
    <?php endif; ?>

	<?php if (Yii::$app->session->hasFlash('error')): ?>
		<div class="alert alert-danger alert-dismissable">
			 <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
			 <h4><i class="icon fa fa-check"></i>Order Placed Errors</h4>
			 <?= Yii::$app->session->getFlash('error') ?>
		</div>
	<?php endif; ?>
<script type="text/javascript">
   var urlPath = '<?= Yii::getAlias('@frontendUrl')?>';
</script>
<?php
$script=<<<JS

$('#search_manu').hide();
JS;
$this->registerJs($script);
?>