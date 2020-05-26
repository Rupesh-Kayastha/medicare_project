<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use edwinhaq\simpleloading\SimpleLoading;
SimpleLoading::widget();

$this->title = 'Search';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['categorySidebar']=true;
//$this->params['data']=$data;
$currentCategory=$this->context->getCurrentCategory();

//echo "<pre>"; var_dump($data['medicineitem']);


?>

 <div class="col-md-12 col-sm-12 col-xs-12" id="content">   
 <div class="">
 	<div class="" >
	<div class="row" id="search_manu" style="margin-top: 10px">
		<div class="col-md-8 col-xs-12">
			<?php $form = ActiveForm::begin(['action'=>['category/search']]); ?>
				<div class="form-group">
					<div class="input-group">
						<input type="text" name="find" placeholder="Enter search keywords here" class="form-control input-lg" id="inputGroup"/>
						<span class="input-group-addon">
							<!-- <button type="submit" name="searchbttn" style="background-color: #8ebe08;box-shadow: none;border: navajowhite;color: #fff;">Search</button> -->
							<input type="submit" value="Search" class="btn btn-primary" style="background-color: #8ebe08;border: none;box-shadow: none;" />
						</span>
					</div>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
		
	</div>
</div>
 </div>         
	<div class="contentText">
		<div class="breadcrumbs" style="background-color: transparent;">
			<h1 style="border: none;color: #000;font-size: 26px;">
			Showing results for <span style="color:#00CC33;">"<?=$find?>"	</span>
			</h1>	
		</div>


		<div class="row margin-top product-layout_width">
			<!-- Product Dynamic Part Start-->
			 <?php
             if(isset($data['getmedicene']) && $data['getmedicene']!=null)
                {
                    $i=0;
                    foreach($data['getmedicene'] as $rkey=>$rval)
                    {
                    $i++;
                ?>
			<div class="product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-12">
				<div class="product-thumb">
					
                    <div class="image">
							<a href="#"><img class="" src="<?=$rval['MedicineImage']?>" width="200" height="200"></a>
					</div>
					<div>
						<div class="caption">
							<h4 class="product_title">

								<a href="#"><?=$rval['MedicineName'];?></a>
							</h4>
							<p>
								<?=$rval['BrandName']?>
							</p>
							
							<p class="price">
								<span class="new_price">&#8377;<?=number_format($rval['DiscountedPrice'],2)?></span> 
								<span class="old_price">&#8377;<?=number_format($rval['RegularPrice'],2)?></span>
								<span class="price-tax" style="display:none;">Ex Tax: €90.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->
					    <?php if($rval['InStock']){ ?>
						<a class="btn book-btn btn-default reg_button" onclick="addtoCart(<?=$rval['MedicineId']?>)">BUY NOW!</a>
					<?php }
					else{?>

							<span class="label label-default"> OUT OF STOCK </span>

					<?php } ?>
						<div class="pull-right" style="display:none;">
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-heart"></i></button>
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-exchange"></i></button>
						</div>
						<!--</div>!-->
					</div>
				</div>
			</div>
			<!-- Product Dynamic Part End -->
			<?php 
				}
			}
			?>
		</div>
		<!-- <div class="row">
			<div class="col-sm-6 text-left"></div>
			<div class="col-sm-6 text-right">Showing 1 to 12 of 12 (1 Pages)</div>
		</div> -->
	</div>

</div>
<script type="text/javascript">
function addtoCart(itemid){
	$.ajax({
		url: "<?= Url::to(['cart/additem']);?>",
		type: "POST",
		data: {
			medicineid: itemid,
		},
		beforeSend:function(json)
		{ 
			SimpleLoading.start('hourglass'); 
		},
		success: function (result) {
			var results = result;
			
			if(results.status == 1){
				alertify.alert('Product added sucessfully').setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
				//location.reload();
				
				var Totalamount = convertIndiancurrency(results.Headercartdetails.Totalamount);
				var TotalItems = '('+results.Headercartdetails.Totalcartitem+')';
				$('#cart_total').html(Totalamount);
				$('.cart_items').html(TotalItems);
			}
			
		},
		complete:function(json)
		{
			SimpleLoading.stop();
		},
	});
}
</script>