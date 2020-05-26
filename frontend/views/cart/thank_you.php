<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'thank you';
?>
<div class="main-content" style="margin-top:40px;">
    <div class="container cart-block-style"> 
		<div class="jumbotron text-center">
			<h1 class="display-3" style="font-weight:normal !important;"><img src="/images/thanku.png" height="140"/><br/>Thank You!</h1>
			<!-- <p class="lead">Please check your email for further instructions.</p> -->
			<hr>
			<p class="lead">
				<a class="btn btn-primary btn-sm" href="<?= Url::to(['site/index']);?>" role="button">Continue to homepage</a>
			</p>
		</div>
	</div>
</div>