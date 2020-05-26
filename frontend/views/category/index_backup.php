<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\helpers\Url;

$this->title = 'Category';
$this->params['breadcrumbs'][] = $this->title;
$this->params['categorySidebar']=true;
$this->params['data']=$data;
$currentCategory=$this->context->getCurrentCategory();

//echo "<pre>"; var_dump($data['medicineitem']);


?>

 <div class="col-md-9 col-sm-8 col-xs-12" id="content">            
	<div class="contentText">
		<div class="breadcrumbs">
			<?= $this->context->getBreadcrumbs();?>			
		</div>

		<div class="row">
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
		</div>

		<div class="row margin-top product-layout_width">
			<!----------------------------------------------------------------->
			<div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="product-thumb">
					<div class="image"><a href="single-product.html"><img class="img-responsive"  src="images/img16.jpg" width="200" height="200"></a></div>
					<div>
						<div class="caption">
							<h4 class="product_title"><a href="single-product.html">Lorem small</a></h4>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry....</p>
							<p class="price">
								<span class="new_price">€110.00</span> 
								<span class="old_price">€122.00</span>
								<span class="price-tax">Ex Tax: €90.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->
						<a class="btn book-btn btn-default reg_button" href="cart.html">BUY NOW!</a>
						<div class="pull-right">
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-heart"></i></button>
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-exchange"></i></button>
						</div>
						<!--</div>!-->
					</div>
				</div>
			</div>
			<!---------------------------------------------------------------------->
			<div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="product-thumb">
					<div class="image"><a href="single-product.html"><img class="img-responsive"  src="images/img17.jpg" width="200" height="200" ></a></div>
					<div>
						<div class="caption">
							<h4 class="product_title"><a href="single-product.html">Simphny</a></h4>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry....</p>
							<p class="price">
								<span class="new_price">€81.00</span> 

								<span class="price-tax">Ex Tax: €90.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->
						<a class="btn book-btn btn-default reg_button" href="cart.html">BUY NOW!</a>
						<div class="pull-right">
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-heart"></i></button>
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-exchange"></i></button>
						</div>
						<!--</div>!-->
					</div>
				</div>
			</div>
			<div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="product-thumb">
					<div class="image"><a href="single-product.html"><img class="img-responsive"  src="images/img4.jpg" width="200" height="200" ></a></div>
					<div>
						<div class="caption">
							<h4 class="product_title"><a href="single-product.html">Lorem block</a></h4>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry....</p>
							<p class="price">
								<span class="new_price">€81.00</span> 

								<span class="price-tax">Ex Tax: €90.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->
						<a class="btn book-btn btn-default reg_button" href="cart.html">BUY NOW!</a>
						<div class="pull-right">
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-heart"></i></button>
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-exchange"></i></button>
						</div>
						<!--</div>!-->
					</div>
				</div>
			</div>

			<div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="product-thumb">
					<div class="image"><a href="single-product.html"><img class="img-responsive"  src="images/d21.jpg" width="200" height="200" ></a></div>
					<div>
						<div class="caption">
							<h4 class="product_title"><a href="single-product.html">Officiis phaedrum</a></h4>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry.... </p>
							<p class="price">
								<span class="new_price">€26.00</span> 
								<span class="old_price">€34.00</span>
								<span class="price-tax">Ex Tax: €21.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->
						<a class="btn book-btn btn-default reg_button" href="cart.html">BUY NOW!</a>
						<div class="pull-right">
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-heart"></i></button>
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-exchange"></i></button>
						</div>
						<!--</div>!-->
					</div>
				</div>
			</div>
			<div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="product-thumb">
					<div class="image"><a href="single-product.html"><img class="img-responsive"  src="images/d23.jpg" width="200" height="200"></a></div>
					<div>
						<div class="caption">
							<h4 class="product_title"><a href="single-product.html">Lorem ipsum dolor</a></h4>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry....</p>
							<p class="price">
								<span class="new_price">€199.00</span> 

								<span class="price-tax">Ex Tax: €180.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->

						<a class="btn book-btn book-btn btn-default reg_button" href="cart.html">BUY NOW!</a>

						<div class="pull-right">
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-heart"></i></button>
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-exchange"></i></button>
						</div>
						<!--</div>!-->
					</div>
				</div>
			</div>
			<div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="product-thumb">
					<div class="image"><a href="single-product.html"><img class="img-responsive"  src="images/d15.jpg" width="200" height="200"></a></div>
					<div>
						<div class="caption">
							<h4 class="product_title"><a href="single-product.html">Cicero famously</a></h4>
							<p>
								Lorem Ipsum is simply dummy text of the printing and typesetting industry.... </p>
							<p class="price">
								<span class="new_price">€171.00</span> 

								<span class="price-tax">Ex Tax: €162.00</span>
							</p>
						</div>
						<!--<div class="button-group">!-->
						<a class="btn book-btn btn-default reg_button" href="cart.html">BUY NOW!</a>
						<div class="pull-right">
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-heart"></i></button>
							<button  title="" data-toggle="tooltip" type="button" class="btn wish_button btn-default reg_button"><i class="fa fa-exchange"></i></button>
						</div>
						<!--</div>!-->
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6 text-left"></div>
			<div class="col-sm-6 text-right">Showing 1 to 12 of 12 (1 Pages)</div>
		</div>
	</div>

</div>
