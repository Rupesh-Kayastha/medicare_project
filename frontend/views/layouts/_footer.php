<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<?= $this->render('_chat.php') ?>
<div class="contentText" style="width:100%;float:left; height:500px;background:url(/images/downloadbg.png); background-size:100% 100%;">
	<div class="container">
		<div class="col-md-12">
			<div class="col-md-4 col-md-offset-1">
				<img src="/images/mob.png" class="img-responsive" style="padding-top:50px;"/>
			</div>
			<div class="col-md-5">
				<h2 style="padding-top:140px; color:#fff; font-size:26px; line-height:1.6; text-align:center;">Download The<span style="color:#CEE984;"> Onestoppharma</span> App Today<br/>
					<span style="font-size:22px;">Available on Play Store Now !</span><br/>
					<span style="line-height:2;"><img src="/images/play.png" height="70"/></span>
				</h2>
			</div>
		</div>
	</div>

</div>
<div id="footer1">
	<div class="container-fluid footer-background">
		<div class="row">
			<div class="container">
				<div class="row">
					<div class="col-md-3 col-sm-5 col-xs-12 txt-center" style="padding-left:0px;">
						<a href="<?= Url::home(); ?>" >
							<span class="logo-text1"  style="text-transform: none;font-size: 16px;letter-spacing: 1px;display: inline-flex;"> <img class="img-responsive" src="<?= Url::home(); ?>/images/amlogo.png" alt="" title="" style="background:#fff; padding:10px; height:70px;"/></span>
						</a>
					</div>
					<div class="col-md-6 col-sm-4 col-xs-12" style="text-align:center;">
						<h3 style="color:#fff;">(A Unit of Arundaya Medicare PVT.LTD.)</h3>
						<div id="footer_menu">
							<a href="<?= Url::home(); ?>">Home</a> | 
							<a href="<?=Url::toRoute(['site/about']);?>">About Us</a> | 
							<a href="<?=Url::toRoute(['site/terms']);?>">Terms & Conditions</a> | 
							<a href="<?=Url::toRoute(['site/contact']);?>">Contact Us</a> | 
							<a href="<?=Url::toRoute(['site/sitemap']);?>">Site Map<span></span></a>
						</div>
						<div class="copyright">
							&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12" style="padding-right:0px;">
						<div id="social_icons" class="pull-right">
							<a href="https://www.facebook.com" class="btn btn-default reg_button"><i class="fa fa-facebook"></i></a>
							<a href="https://www.twitter.com" class="btn btn-default reg_button"><i class="fa fa-twitter"></i></a>
							<a href="https://www.linkedin.com" class="btn btn-default reg_button"><i class="fa fa-linkedin"></i></a>	
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>

</div>
<a style="display: none" href="javascript:void(0);" class="scrollTop back-to-top" id="back-to-top">
	<i class="fa fa-chevron-up"></i>
</a>