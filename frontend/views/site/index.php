<?php
use yii\helpers\Url;
use common\models\Cart;
use yii\db\Expression;
use common\models\Medicine;
/* @var $this yii\web\View */

$this->title = 'Ashoka Medicines';

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
	$resullt = "(".Yii::$app->user->identity->EmployeeId.")";
}else{
	$resullt = "";
}

/********************/

?>

<div class="site-index" >

<div class="col-md-12" style="padding:0px; margin-top:10px; ">
<div class="col-md-9" style="padding-left:0px; padding-right:10px;">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
 -->
    <div class="carousel-inner">
       <?php
        $i=0;
            foreach($banner as $key=>$value)
              
            {
            ?>
            <div class="item <?php if($i == 0){ echo 'active'; } ?>">
              <a href="<?=$value->WebLink?>">
              <img src="<?=Yii::getAlias('@storageUrl')?>/imageupload/<?=$value->BannerImage?>" alt="<?=$value->BannerImage?>" style="width:100%;">
              </a>
            </div>
           
             <?php
             $i++;
            }
             ?>

   

    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>   
  
  </div>

  </div>
    <div class="col-md-3" style="padding-right:0px; padding-left:0px;">
  		<a href="<?=Url::toRoute(['site/uploadprescription']);?>"><img src="images/smallbanner1.jpg" class="img-responsive" style="margin-bottom:10px; height:245px; width:100%; "/></a>
		<img src="images/smallbanner2.jpg" class="img-responsive" style="height:245px; width:100%;"/>
  </div>
</div>
<div class="bg-color" style="width:100%; height:35px; float:left; padding:8px;">
	<div class="container">
		<p style="color:#fff;"><marquee behavior="scroll"   scrolldelay="85"  direction="left" style="float:right; width:88%;">MASK & SANITISERS ARE AVALIBLE WITH US</marquee></p>
	</div>
</div>

<?php 

$Best_Seller = Medicine::find()
    ->where(['BestSeller'=>1,'IsDelete'=>0])
    ->limit(10)->all();

if($Best_Seller){
?>                                   
<div class="contentText">
<div class="container">
<div class="row">

<div class="col-md-12 col-sm-12 col-xs-12"  >
<h3>Our Best Seller</h3>
        <div class="bg_best">
            <div class="owl-carousel" id="best_seller">
             <?php foreach($Best_Seller as $bs){ ?>
                <div class="item">
                     <span>
                        <?php
                            if ($bs->MediceneImage !='') 
                            {
                                $image = $bs->MediceneImage;
                            }
                            else
                            {
                                $image = Yii::getAlias('@storageUrl')."/default/medical.png";
                            }
                        ?>
                        <a href="#"><img class="carasoul_image" src="<?=$image?>" style=" height:180px; padding:15px;"></a>
                     </span>
                    <div class="caption">
                        <h4 class="product_title">
                            <a href="#"><?=$bs->Name;?></a>
                        </h4>
                        <p style="height:50px; margin-bottom:0px; padding-left:0px; padding-right:0px;">
                            <?=$bs->brand->Name;?>
                        </p>
                        <p class="price">
                            <span class="new_price">&#8377;<?=number_format($bs->DiscountedPrice,2)?></span> 
                            <span class="old_price">&#8377;<?=number_format($bs->RegularPrice,2)?></span>
                           
                        </p>
                    </div>
                    <?php if($bs->InStock){ ?>
						<a class="btn book-btn btn-default reg_button" onclick="addtoCart(<?=$bs->MedicineId?>)"><i class="fa fa-shopping-cart"></i> BUY NOW !</a>
					<?php }
					else{?>

							<span class="label label-default"> OUT OF STOCK </span>

					<?php } ?>
                    
                    
                </div>
             <?php } ?>
                
            </div>

<?php
$script = <<< JS
    jQuery(document).ready(function () {
        jQuery('#best_seller').owlCarousel({
            margin:true,
            autoplayHoverPause:true,
            nav: true,
            items:5,
            loop:true,
            autoplay:true,
            autoplayTimeout:1000,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: false
                },
                1000: {
                    items: 5,
                    nav: true,
                    loop: true,
                    margin: 20
                }

            }
        })
    })
JS;
$this->registerJs($script);
?>
            </div>
           
        </div>
    </div>
</div>
</div>
<?php

}
?>

<div class="contentText" style="float:left; background:#f8f8f8; width:100%; margin-bottom:0px;">
	<div class="container">
		<div class="col-md-12" style="padding:0px;padding-top:40px; padding-bottom:40px;">
			<div class="col-md-4" style="padding-left:0px;" >
				<div class="sbox">
					<div class="col-md-3" style="padding:0px;">
						<img src="/images/icon1.png" class="img-responsive"/>
					</div>
					<div class="col-md-9" style="padding-right:0px;">
						<h4 style="font-size: 16px;color: #555;">Maximize Your Savings</h4>
						<p style="font-size:12px; color:#888; line-height:1.7; text-align:left; padding:0px;">With FlexiRewards,you choose the reward-cash discount or free goods.</p>
					</div>
				</div>
				
			</div>
			<div class="col-md-4">
				<div class="sbox">
					<div class="col-md-3" style="padding:0px;">
						<img src="/images/icon2.png" class="img-responsive"/>
					</div>
					<div class="col-md-9" style="padding-right:0px;">
						<h4 style="font-size: 16px;color: #555;">Click and Pick</h4>
						<p style="font-size:12px; color:#888; line-height:1.7; text-align:left; padding:0px;">Click to buy a range of product across categories-Pharmacy,OTC...</p>
					</div>
				</div>
			</div>
			<div class="col-md-4" style="padding-right:0px;">
				<div class="sbox">
					<div class="col-md-3" style="padding:0px;">
						<img src="/images/icon3.png" class="img-responsive"/>
					</div>
					<div class="col-md-9" style="padding-right:0px;">
						<h4 style="font-size: 16px;color: #555;">Home Delivery</h4>
						<p style="font-size:12px; color:#888; line-height:1.7; text-align:left; padding:0px;">We offer convenient home delivery of medicines & general goods.</p>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

<div class="contentText" style="background:#fff; float:left;">

		
			<div class="col-md-12" style="padding-top:25px; padding-bottom:25px;">
				<p style="padding-left:20px; padding-right:20px; color:#333;">
					<span style="font-weight:bold; font-size:16px;">Stay Healthy with Onestoppharma: Your Favourite Online Pharmacy and Healthcare Platform</span><br/><br/>
We Bring Care to Health.<br/><br/>

Onestoppharma is India's leading digital healthcare platform. From doctor consultations on chat to online pharmacy and lab tests at home: we have it all covered for you. Having delivered over 25 million orders in 1000+ cities till date, we are on a mission to bring "care" to "health" to give you a flawless healthcare experience.<br/><br/>

<span style="font-weight:bold; font-size:16px;">Onestoppharma: Your Favourite Online Pharmacy!</span><br/><br/>

Onestoppharma is India's leading online chemist with over 2 lakh medicines available at the best prices. We are your one-stop destination for other healthcare products as well, such as over the counter pharmaceuticals, healthcare devices and homeopathy and ayurveda medicines.<br/><br/>

With Onestoppharma, you can buy medicines online and get them delivered at your doorstep anywhere in India! But, is ordering medicines online a difficult process? Not at all! Simply search for the products you want to buy, add to cart and checkout. Now all you need to do is sit back as we get your order delivered to you.<br/><br/>

In case you need assistance, just give us a call and we will help you complete your order.
<br/><br/>
Don't want to go through the hassle of adding each medicine separately? You can simply upload your prescription and we will place your order for you. And there is more! At Onestoppharma, you can buy health products and medicines online at best discounts.
<br/><br/>
Now, isn't that easy? Why go all the way to the medicine store and wait in line, when you have Onestoppharma Pharmacy at your service.<br/><br/>

<span style="font-weight:bold; font-size:16px;">The Services We Offer</span>
<br/><br/>
Being India's leading digital healthcare platforms, we take care of all your health needs. Besides delivering medicines at your doorstep, we provide accurate, authoritative & trustworthy information on medicines and help people use their medicines effectively and safely. We also facilitate lab tests at home. You can avail over 2,000 tests and get tested by 120+ top and verified labs at the best prices. Need to consult a doctor? On our platform, you can talk to over 20 kinds of specialists in just a few clicks.
<br/><br/>
We've made health care accessible to millions by giving them quality care at affordable prices. With a presence in over 1000 cities, we plan to keep going until everyone is well.
<br/><br/>
Customer centricity is the core of our values. Our team of highly trained and experienced doctors, phlebotomists and pharmacists looks into each order to give you a fulfilling experience.
<br/><br/>
Visit our online medical store now, and start placing your order.<br/>
Stay Healthy!
				</p>
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
        // beforeSend:function(json)
        // { 
        //     SimpleLoading.start('hourglass'); 
        // },
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
        // complete:function(json)
        // {
        //     SimpleLoading.stop();
        // },
    });
}
</script>

