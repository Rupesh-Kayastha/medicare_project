<?php
use kartik\widgets\SideNav;
use frontend\widgets\CategorySidebar;
$CategoryFiletrs=$data['CategoryFiletrs'];

?>
<div class="col-md-3 col-sm-4 col-xs-12 left_sidebar1" style="padding-left:0px; margin-top:10px;">
	<div id="left_part">
		<div class="bs-example">
			<div class="panel-group" id="accordion">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="infoBoxHeading">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Categories</a>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
								<i class="fa fa-chevron-up indicator pull-right"></i>
							</a>
						</div>
					</div>
					<div id="collapseOne" class="panel-collapse collapse in">
						<div class="panel-body">
							<div class="infoBoxContents">
								<div class="shopby">
									<div class="shopby-heading" style="display:none;">
										<span></span>
									</div>
									<div class="shopby-content">
										
											<?php 
											
											
											echo CategorySidebar::widget([
												'items' => $this->context->getCategoryFiletrs(),
												'options'=>['class'=>'sidebar-nav']
												
											]);
$script=<<<JS
$('.toggle-opener').click(function (event) {
	event.preventDefault(); // cancel the event
	
	$(this).parent().parent().children('ul').toggle();
	$(this).toggleClass("fa-minus fa-plus");
	return false;
});
JS;

$this->registerJs($script);
											
											
											?>
										
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				
				
				
				

			</div>

		</div>
	</div>
	
	<div class="col-md-12" style="padding:0px; ">
		<img src="/images/leftbanner.jpg" class="img-responsive" style="padding:5px; border:1px solid #dedede;"/>
	</div>
	<script>

	</script>
<?php
$script=<<<JS
function toggleChevron(e) {
	$(e.target)
			.prev('.panel-heading')
			.find("i.indicator")
			.toggleClass('fa-chevron-down fa-chevron-up');
}
$('#accordion').on('hidden.bs.collapse', toggleChevron);
$('#accordion').on('shown.bs.collapse', toggleChevron);

$(".side-nav i.indicator").click(function(){
  $(this).toggleClass('fa-chevron-down fa-chevron-up');
}); 
JS;

$this->registerJs($script);

?>
</div>