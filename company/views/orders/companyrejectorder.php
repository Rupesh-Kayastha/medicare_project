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
    'modelClass' => 'Reject Orders: Pay To Company',
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alert alert-success alert-dismissable" style="display:none;">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4><i class="icon fa fa-check"></i>Order Confirm!</h4>
	<span id="success_msg"></span>
</div>
<div class="alert alert-danger alert-dismissable" style="display:none;">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4><i class="icon fa fa-check"></i>Order Placed Errors</h4>
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
						'filter' => ['3'=>'Direct to Company','2'=>'EMI'],
						'format'=>'raw',    
						'value' => function($model, $key, $index)
						{   
							if($model->PaymentType == 3)
							{
								return "Direct to Company";
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
					
					'CreatedDate',
					[
						'class' => 'yii\grid\ActionColumn',
						'headerOptions' => ['width' => '70'],
						'template' => '{view} {link}',
						'buttons' => [
							'view' => function ($url, $model) {
								return  Html::a('<i class="fa fa-eye"></i>', $url."&ref=company-reject-order", 
								[ 'title' => Yii::t('backend', 'View'), 'class'=>'btn btn-primary btn-xs', ]) ;
							},
						],
					],
					
                ],
				
            ]); ?>
            
            <?php Pjax::end(); ?>


        </div>


        <?php Panel::end() ?> 
    </div>
</div>