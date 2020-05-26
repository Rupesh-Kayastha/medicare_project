<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use edwinhaq\simpleloading\SimpleLoading;
SimpleLoading::widget();
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Manage {modelClass}', [
    'modelClass' => 'Open Orders',
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.alert_textarea{
	width: 100%;
    border: 1px solid #dedede;
    border-radius: 4px;
}
textarea:focus {
    outline-style: none !important;
    outline-width: 1px !important;
	 border: 1px solid #77a8d3 !important;
}
</style>
<div class="alert alert-success alert-dismissable" style="display:none;">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<span id="success_msg"></span>
</div>
<div class="alert alert-danger alert-dismissable" style="display:none;">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<span id="error_msg"></span>
</div>
<div class="row">
    <div class="col-md-12">

        <?php         Panel::begin(
        [
        'header' => Html::encode($this->title),
        'icon' => 'users',
        ]
        )
         ?> 


        <div class="orders-index">


                <?php Pjax::begin(); ?>

            <div class="container">
                <div class="box-header with-border">&nbsp;</div>
            </div>

            <?= \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover' => true,
                'filterModel' => $searchModel,
				'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

					'OrderIdentifier',
					[
						'attribute' => 'EmployeeName',
						'value'=>'employee.EmployeeName',
						'enableSorting'=>true,											
					],
					[
						'attribute'=>'PaymentType',
						'header'=>'Payment Type',
						'filter' => ['1'=>'COD','2'=>'Online','3'=>'Direct','4'=>'EMI'],
						'format'=>'raw',    
						'value' => function($model, $key, $index)
						{   
							if($model->PaymentType == 1)
							{
								return "COD";
							}
							else if($model->PaymentType == 2)
							{
							  return "Online";
							}
							else if($model->PaymentType == 3)
							{
							  return "Direct";
							}
							else
							{
								return "EMI";
							}
						},
					],
					[
						'attribute'=>'MonthlySubscription',
						'header'=>'Monthly Subscription',
						'filter' => ['1'=>'Yes','0'=>'No'],
						'format'=>'raw',    
						'value' => function($model, $key, $index)
						{   
							if($model->MonthlySubscription == 1)
							{
								return "<span class='color green'>Yes</span>";
							}
							else if($model->MonthlySubscription == 0)
							{
							  return "<span class='color red'>No</span>";
							}
							
						},
					],
					
					'OrderTotalPrice',
					/*
					[
						'attribute'=>'OrderType',
						'header'=>'Order Type',
						'filter' => ['1'=>'Order By Support','2'=>'Order By Self'],
						'format'=>'raw',    
						'value' => function($model, $key, $index)
						{   
							if($model->OrderType == 0)
							{
								return "Not Set";
							}
							else if($model->OrderType == 1)
							{
							  return "Order By Support";
							}
							else
							{
								return "Order By Self";
							}
						},
					],
					*/
					'CreatedDate',
					[
						'attribute'=>'',
						'content' => function($model,$key,$index, $column) {
							if($model->OrderStatus == 0){
								$val = "Confirm";
								$status = 1;
								$class = 'btn btn-success';
							}else{
								$val = "In-Activate";
								$status = 0;
								$class = 'btn btn-danger';
							}
							
							return Html::button($val,$options = [
								'onclick'=>'orderConfirm('.$model->OrderId.',$(this).parents("tr").data("key"))',
								'class'=>$class,
							]);
						}
					],
					[
						'attribute'=>'',
						'content' => function($model,$key,$index, $column) {
							if($model->OrderStatus == 0){
								$val = "Reject";
								$status = 1;
								$class = 'btn btn-danger';
							}else{
								$val = "In-Activate";
								$status = 0;
								$class = 'btn btn-danger';
							}
							
							return Html::button($val,$options = [
								'onclick'=>'orderReject('.$model->OrderId.',$(this).parents("tr").data("key"))',
								'class'=>$class,
							]);
						}
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'headerOptions' => ['width' => '70'],
						'template' => '{view} {link}',
						'buttons' => [
							'view' => function ($url, $model) {
								return  Html::a('<i class="fa fa-eye"></i>', $url."&ref=open-order", 
								[ 'title' => Yii::t('backend', 'View'), 'class'=>'btn btn-primary btn-xs', ]) ;
							},
						],
					],
					
                ],
				'tableOptions' =>['class' => 'table dataTable table-bordered table-striped table-hover','id'=>'open_order']
            ]); ?>
            
            <?php Pjax::end(); ?>


        </div>


        <?php Panel::end() ?> 
    </div>
</div>
<script type="text/javascript">
	function orderConfirm(OrderId,datakey){
		$('.alert-success').hide();
		$('.alert-danger').hide();
		alertify.confirm('Are you sure want to confirm the order.....', function(){
			$.ajax({
				url: "<?= Url::to(['orders/order-confirm']);?>",
				type: "POST",
				data: {
					OrderId: OrderId,
				},
				beforeSend:function(json)
				{ 
					SimpleLoading.start('reload'); 
				},
				success: function (result) {
					if(result.status == 1){
						$('#success_msg').html(result.msg);
						$('.alert-success').show();
						setTimeout(function(){ $('.alert-success').hide('slow'); }, 8000);
						$('#open_order tr[data-key="'+datakey+'"]').remove();
					}else{
						$('#error_msg').html(result.msg);
						$('.alert-danger').show();
						setTimeout(function(){ $('.alert-danger').hide('slow'); }, 8000);
					}
				},
				complete:function(json)
				{
					SimpleLoading.stop();
				},
			});
		}).set('labels', {ok:'Confirm', cancel:'Cancel'}).setHeader('<em class="alert_header"> Arunodayamedicare </em> ');
	
	}
	function orderReject(OrderId,datakey){
		$('.alert-success').hide();
		$('.alert-danger').hide();
		
		alertify.confirm().setting({
			'message': '<label>Comment</label><textarea row="9" col="9" id="ordercomment" class="alert_textarea" placeholder="Please enter comment for reject"></textarea>',
			'onok': function(){
				$.ajax({
					url: "<?= Url::to(['orders/order-reject']);?>",
					type: "POST",
					data: {
						OrderId: OrderId,
						Comment: $('#ordercomment').val(),
					},
					beforeSend:function(json)
					{ 
						SimpleLoading.start('reload'); 
					},
					success: function (result) {
						if(result.status == 1){
							$('#success_msg').html(result.msg);
							$('.alert-success').show();
							setTimeout(function(){ $('.alert-success').hide('slow'); }, 8000);
							$('#open_order tr[data-key="'+datakey+'"]').remove();
						}else{
							$('#error_msg').html(result.msg);
							$('.alert-danger').show();
							setTimeout(function(){ $('.alert-danger').hide('slow'); }, 8000);
						}
					},
					complete:function(json)
					{
						SimpleLoading.stop();
					},
				});
				
			}
		}).set('labels', {ok:'Reject', cancel:'Cancel'}).setHeader('<em class="alert_header"> Arunodayamedicare </em> ').show();
	}
</script>


