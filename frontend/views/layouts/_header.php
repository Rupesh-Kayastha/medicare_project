<?php 
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\Cart;
if(Yii::$app->session->get('CartIdentifire')!=""){
	$CartIdentifire = Yii::$app->session->get('CartIdentifire');
}else{
	$CartIdentifire = "";
}
$cart_obj = new Cart();
$cartdetails = $cart_obj->getHeadercartdetails($CartIdentifire);
$no_cartitem = $cartdetails['Totalcartitem'];
$cartamount = $cartdetails['Totalamount'];

/*********For Test purpose**************/
if(isset(Yii::$app->user->identity->EmployeeId)){
	$resullt = "(".Yii::$app->user->identity->EmpId.")";
}else{
	$resullt = "";
}

/********************/
?>

<div class="container-fluid" id="myHeader">
<div class="container">
	<div class="row">
		<div class="col-md-2 col-sm-2 col-xs-2" id="logo" style="padding-left:0px;" >
			<a href="<?= Url::home(); ?>" class="logo-text">
				<img class="img-responsive" src="<?= Url::home(); ?>/images/logo.png" alt="" title="" width="100" height="237" />
			</a>		
		</div>
		<div class="col-md-2 col-sm-12 col-xs-12" style="display:none " id="navbar_hide" >
			<nav  role="navigation" class="navbar navbar-inverse">
				<a href="<?= Url::home(); ?>" class="logo-text">
				<img class="img-responsive" src="<?= Url::home(); ?>/images/logo.png" alt="" title="" width="100" height="237" />
				</a>
				<div id="nav">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar" style="background: #8EBE08; border: none; margin-right: 0">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</div>
					<div class="collapse navbar-collapse" id="myNavbar">
						<?= $this->render('_sitemenu.php') ?>
					</div>
				</div>
			</nav>
		</div>
		<div class="col-md-4">
			<?php $form = ActiveForm::begin(['action'=>['category/search']]); ?>
			  <div class="row">
				
				  <div class="input-group" style="margin-top:22px;">
					<input type="text" name="find" class="form-control" placeholder="Search" id="inputGroup"/>
					<div class="input-group-btn">
					  <button class="btn btn-primary" type="submit">
						<span class="glyphicon glyphicon-search"></span>
					  </button>
					</div>
				  </div>
				
			  </div>
			<?php ActiveForm::end(); ?>
		</div>
		
		<div class="col-md-4">
			<div id="bottom_right" style="margin-top:20px; float:right;">
						<div class="row">
							<div class="col-md-8 col-xs-6 wd_auto">
								<div class="right">
									<div class="login">
										<?php 	if (Yii::$app->user->isGuest) { 	?>
													<a class="btn btn-primary reg_button" href="<?= Url::to(['site/login']); ?>" style="line-height:1.8;"><i class="fa fa-sign-in" style="font-size:16px;"></i> Login</a> 
										<?php 	}
												else{ 	?>
													<a class="btn btn-warning" href="#/" onclick="orderbysupport();">Support</a> 
														<a class="btn btn-primary" data-method="post" href="<?= Url::to(['site/logout']); ?>"><i class="fa fa-sign-out" style="font-size:16px;"></i> Logout</a> 
										<?php 	}		?>
									</div>			
								</div>
							</div>
							<?php 	if (!Yii::$app->user->isGuest) { 	?>
							<div class="dropdown-bn wd-33 col-md-4 remove_PL col-xs-4">
								<div class="row">
									<div class=" pl-0 col-md-12 col-xs-12">

										<div class="dropdown btn-group">
											<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-user-circle-o" style="font-size:20px;"></i> My Account
												<span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="<?= Url::to(['site/orders']); ?>">Orders</a></li>
												<li><a href="<?= Url::to(['site/profile']); ?>">Profile</a></li>
												<li style="display: none;"><a href="<?= Url::to(['site/subscriptions']); ?>">Subscriptions</a></li>
												<li><a href="<?= Url::to(['site/emi']); ?>">EMI's</a></li>
												<li><a href="<?= Url::to(['site/prescription']); ?>">Prescription</a></li>
												<li><a href="<?= Url::to(['site/addnewaddress']); ?>">Add New Address</a></li>
											</ul>
										</div>
									</div>
								</div>

                            </div>
							<?php } ?>
							
						</div>					
					</div>
		</div>
		
		<div class="col-md-2  col-sm-6 col-xs-12" >
			
				<div id="top_right">
					<div id="cart">
						<div class="text">
							<a href="<?= Url::to(['cart/viewcart']); ?>">
								<div class="img">
									 <i class="fa fa-cart-plus" style="font-size:24px; color:#1b86b7;"></i>
								</div>
								<span class="cart_total">
									&#x20B9;<span id="cart_total"><?=number_format($cartamount,2);?></span>
								</span>
								<span class="cart_items">(<?=$no_cartitem;?>)</span>
							</a>
						</div> 
					</div>
					
				</div>
		</div>
		
		
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
<script>
window.onscroll = function() {myFunction()};

// Get the header
var header = document.getElementById("myHeader");

// Get the offset position of the navbar
var sticky = header.offsetTop;

// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
} 
</script>