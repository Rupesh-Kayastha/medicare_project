<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="col-md-3 left_col menu_fixed">
	<div class="scroll-view">

		<div class="navbar nav_title" style="border: 0;">
			<a href="<?= Url::to(['/']);?>" class="site_title"><i class="fa fa-medkit"></i> <span><?php echo Yii::$app->user->identity->EmployeeName ?>!</span></a>
		</div>
		<div class="clearfix"></div>

		<!-- menu prile quick info -->
		<div class="profile clearfix">
			<div class="profile_pic">
				<img src="<?= (Yii::$app->user->identity->company->Logo)? Yii::$app->user->identity->company->Logo:Yii::getAlias('@storageUrl').'/default/default_company.png'?>" alt="..." class="img-circle profile_img">
			</div>
			<div class="profile_info">
				<span>Welcome,</span>
				<h2><?php echo Yii::$app->user->identity->EmployeeName ?></h2>
			</div>
		</div>
		<!-- /menu prile quick info -->

		<br />
		<!-- sidebar menu -->
		<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

			<div class="menu_section">
				<h3>Menu</h3>
				
				
				<?=
				\yiister\gentelella\widgets\Menu::widget(
					[
						"items" => [
							["label" => "Home", "url" => ["/"], "icon" => "home"],
							[
								"label" => "Mannage", 
								"url" => "#", 
								"icon" => "home",
								"items"=>[
									[	
										"label" => "Employees", 
										"url" => '#', 
										"icon" => "home",
										"items"=>[
											["label" => "All Employee", "url" => ["employee/"], "icon" => "home",'active' => in_array($this->context->route,['employee/index','employee/update'])
											],
											["label" => "Add Employee", "url" => ["employee/create"], "icon" => "home"]
										
										]
									],
									[	
										"label" => "EMI Plans", 
										"url" => '#', 
										"icon" => "home",
										"items"=>[
											["label" => "All EMI Plans", "url" => ["emi-plans/"], "icon" => "home",'active' => in_array($this->context->route,['emi-plans/index','emi-plans/update'])
											],
											["label" => "Add EMI Plan", "url" => ["emi-plans/create"], "icon" => "home"]
										
										]
									]
								]
							],
							[
								"label" => "Orders", 
								"url" => "#", 
								"icon" => "home",
								'items' => [
									[
										"label" => "Pay To Company", 
										"url" => "#", 
										"icon" => "home",
										'items' => [
											[
												"label" => "Open Order",
												"url" => ["orders/company-open-order"], 
												"icon" => "home",
												"active" => in_array($this->context->route,['orders/company-open-order'])
											],
											[
												"label" => "Confirm Order", 
												"url" => ["orders/company-confirm-order"], 
												"icon" => "home",
												"active" => in_array($this->context->route,['company/company-confirm-order'])
											],
											[
												"label" => "Reject Order", 
												"url" => ["orders/company-reject-order"], 
												"icon" => "home",
												"active" => in_array($this->context->route,['orders/company-confirm-order'])
											]
										
										]
									],
									[	
										"label" => "Pay Self", 
										"url" => "#", 
										"icon" => "home",
										'items' => [
											[
												"label" => "Open Order",
												"url" => ["orders/self-open-order"], 
												"icon" => "home",
												"active" => in_array($this->context->route,['orders/self-open-order'])
											],
											[
												"label" => "Confirm Order", 
												"url" => ["orders/self-confirm-order"], 
												"icon" => "home",
												"active" => in_array($this->context->route,['orders/self-confirm-order'])
											],
											[
												"label" => "Reject Order", 
												"url" => ["orders/self-reject-order"], 
												"icon" => "home",
												"active" => in_array($this->context->route,['orders/self-confirm-order'])
											]
										
										]
									]
								]
							],
							[
								"label" => "EMI Schedules", 
								"url" => ["emi-schedules/"], 
								"icon" => "home",								
							]
						],
					]
				)
				?>
			</div>

		</div>
		<!-- /sidebar menu -->

		<!-- /menu footer buttons -->
		<div class="sidebar-footer hidden-small">
			<a data-toggle="tooltip" data-placement="top" title="Settings">
				<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="FullScreen" onclick="openFullscreen();">
				<span  id="fullscreen" class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="Lock">
				<span  class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
			</a>
			<a href="<?= Url::to(['site/logout']);?>" data-toggle="tooltip" data-method="post" data-placement="top" title="Logout">
				<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
			</a>
		</div>
		<!-- /menu footer buttons -->
	</div>
</div>

<script>
/* Get the documentElement (<html>) to display the page in fullscreen */
var FullscreenElem = document.documentElement;
var isFullScreen=false;
/* View in fullscreen */
function openFullscreen() {
	
	if(isFullScreen){
		
		  if (document.exitFullscreen) {
			document.exitFullscreen();
		  } else if (document.mozCancelFullScreen) { /* Firefox */
			document.mozCancelFullScreen();
		  } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
			document.webkitExitFullscreen();
		  } else if (document.msExitFullscreen) { /* IE/Edge */
			document.msExitFullscreen();
		  }
			isFullScreen=false;
	}
	else{
		  if (FullscreenElem.requestFullscreen) {
			FullscreenElem.requestFullscreen();
		  } else if (elem.mozRequestFullScreen) { /* Firefox */
			FullscreenElem.mozRequestFullScreen();
		  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
			FullscreenElem.webkitRequestFullscreen();
		  } else if (elem.msRequestFullscreen) { /* IE/Edge */
			FullscreenElem.msRequestFullscreen();
		  }
			isFullScreen=true;
	}
 
}


</script>