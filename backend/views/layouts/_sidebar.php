<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="col-md-3 left_col menu_fixed">
	<div class=" scroll-view">

		<div class="navbar nav_title" style="border: 0;">
			<a href="<?= Url::to(['/']);?>" class="site_title"><i class="fa fa-medkit"></i> <span><?php echo Yii::$app->user->identity->username ?>!</span></a>
		</div>
		<div class="clearfix"></div>

		<!-- menu prile quick info -->
		<div class="profile clearfix">
			<div class="profile_pic">
				<img src="<?= Yii::getAlias('@storageUrl').'/default/user.png'?>" alt="..." class="img-circle profile_img">
			</div>
			<div class="profile_info">
				<span>Welcome,</span>
				<h2><?php echo Yii::$app->user->identity->username ?></h2>
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
								"label" => "Medicines", 
								"url" => '#', 
								"icon" => "home",
								"items"=>[
									["label" => "Medicines Categories", "url" => ["medicine-category/"], "icon" => "home"],
									["label" => "All Medicines", "url" => ["medicine/"], "icon" => "home",'active' => in_array($this->context->route,['medicine/index','medicine/update'])
								],
								["label" => "Add Item", "url" => ["medicine/create"], "icon" => "home"],
								["label" => "Import Medicines", "url" => ["medicine/importmedicines"], "icon" => "home"]
								
							]
						],
						[	
							"label" => "Companies", 
							"url" => '#', 
							"icon" => "home",
							"items"=>[
								["label" => "All Companies", "url" => ["company/"], "icon" => "home",'active' => in_array($this->context->route,['company/index','company/update','company/view'])
							],
							["label" => "Add Company", "url" => ["company/create"], "icon" => "home"]
							
						]
					],
					[	
						"label" => "All Orders", 
						"url" => '#', 
						"icon" => "first-order",
						"items"=>[
							["label" => "Open Order", "url" => ["orders/open-order"], "icon" => "first-order",'active' => in_array($this->context->route,['orders/open-order','orders/view'])],
							["label" => "Confirm Order", "url" => ["orders/confirm-order"], "icon" => "first-order",'active' => in_array($this->context->route,['orders/confirm-order','orders/view'])],
							["label" => "Reject Order", "url" => ["orders/reject-order",], "icon" => "first-order",'active' => in_array($this->context->route,['orders/reject-order','orders/view'])],
						]
					],
					["label" => "Logistics", "url" =>  'https://app.shiprocket.in/orders/processing', 'template' => '<a href="{url}" target="_blank" >{icon}<span>{label}</span>{badge}</a>'  ,"icon" => "list-ul"],                           
					[	
						"label" => "Banner", 
						"url" => '#', 
						"icon" => "upload",
						"items"=>[
							["label" => "Upload Banner", "url" => ["home-banner/create",], "icon" => "upload"],
							["label" => "View Banner", "url" => ["home-banner/index",], "icon" => "list-ul"],
							
						]
					],
					[	
						"label" => "Hospital", 
						"url" => '#', 
						"icon" => "upload",
						"items"=>[
							["label" => "Upload Hospitals", "url" => ["hospital/create",], "icon" => "upload"],
							["label" => "View Hospitals", "url" => ["hospital/index",], "icon" => "list-ul"],
							
						]
					],
					
					["label" => "View Contactlist", "url" => ["site/contactlist",], "icon" => "list-ul"],
					["label" => "View Web Prescriptions", "url" => ["web-prescription/index",], "icon" => "list-ul"],	
					
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