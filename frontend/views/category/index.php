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

$this->title = 'Category';
$this->params['breadcrumbs'][] = $this->title;
$this->params['categorySidebar']=true;
$this->params['data']=$data;
$currentCategory=$this->context->getCurrentCategory();

//echo "<pre>"; var_dump($data['medicineitem']);


?>

 <div class="col-md-9 col-sm-8 col-xs-12" id="content">   
 
 <div class="">
 	<div class="" >
	<div class="row" id="search_manu" style="margin-top: 10px">
		<div class="col-md-12 col-xs-12">
			<img class="img-responsive" src="<?= Url::home(); ?>/images/med.jpg" style="margin-bottom:10px;"/>
			<?php $form = ActiveForm::begin(['action'=>['category/search']]); ?>
				<div class="form-group">
					<div class="input-group">
						<input type="text" name="find" placeholder="Enter search keywords here" class="form-control input-lg" id="inputGroup"/>
						<span class="input-group-addon">
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
		<div class="breadcrumbs">
			<?= $this->context->getBreadcrumbs();?>			
		</div>

		<!-- <div class="row">
			<div class="col-md-4">
				<div class="btn-group hidden-xs">
					<a class="btn btn-default" id="list-view" href="product-list.html"><i class="fa fa-th-list"></i></a>
					<a class="btn btn-default" id="grid-view"><i class="fa fa-th"></i></a>
				</div>
			</div>
			<div class="col-md-2 text-right txt-left">
				<label for="input-sort" class="control-label" style="margin-top: 7px">Sort By:</label>
			</div>
			<div class="col-md-3 text-right">
				<select  class="form-control" id="input-sort">
					<option selected="selected">Default</option>
					<option>Name (Z - A)</option>
				</select>
			</div>
			<div class="col-md-1 text-right txt-left">
				<label for="input-limit" class="control-label" style="margin-top: 7px">Show:</label>
			</div>
			<div class="col-md-2 text-right">
				<select  class="form-control" id="input-limit">
					<option selected="selected">15</option>
					<option>25</option>
					<option>50</option>
					<option>75</option>
				</select>
			</div>
		</div> -->

		<div class="row margin-top product-layout_width">
			<!-- Product Dynamic Part Start-->
			<?php 
			if(isset($data['medicineitem']) && $data['medicineitem']!=null){
				foreach($data['medicineitem'] as $itemkey=>$itemvalue){
			?>
			<div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="product-thumb">

					
						<div class="image">
							<a href="#"><img class="" src="<?=$itemvalue['MedicineImage']?>" width="160" height="160"></a>
						</div>
					
					<div>
						<div class="caption">
							<h4 class="product_title">
								<a href="#"><?=$itemvalue['MedicineName'];?></a>
							</h4>
							<p >
								<?=$itemvalue['BrandName']?>
							</p>
							<p class="price">
								<span class="new_price">&#8377;<?=number_format($itemvalue['DiscountedPrice'],2)?></span> 
								<span class="old_price">&#8377;<?=number_format($itemvalue['RegularPrice'],2)?></span>
								<span class="price-tax" style="display:none;">Ex Tax: €90.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->
					    <?php if($itemvalue['InStock']){ ?>
						<a class="btn book-btn btn-default reg_button" onclick="addtoCart(<?=$itemvalue['MedicineId']?>)"><i class="fa fa-shopping-cart"></i> BUY NOW !</a>
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
		<div class="row">
			<div class="col-sm-6 text-left"></div>
			<div class="col-sm-6 text-right"><?= yii\widgets\LinkPager::widget(['pagination' => $pages]);?></div>
		</div>
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