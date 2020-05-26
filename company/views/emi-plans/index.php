<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EmiPlansSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('company', 'Manage {modelClass}', [
    'modelClass' => 'Emi Plans',
]);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">

        <?php         Panel::begin(
        [
        'header' => Html::encode($this->title),
        'icon' => 'users',
        ]
        )
         ?> 


        <div class="emi-plans-index">


                <?php Pjax::begin(); ?>

            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('company', 'Create Emi Plans'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                </div>
            </div>

                <?= \yiister\gentelella\widgets\grid\GridView::widget(
					[
						'dataProvider' => $dataProvider,
						'hover' => true,
						'filterModel' => $searchModel,
						'columns' => [
							['class' => 'yii\grid\SerialColumn'],
							//'EmiPlanId',
							// 'EmiPlanCompanyId',
							'EmiPlanName',
							'EmiPlanPeriod',
							'EmiPlanOrderMinAmount',
							[
								'attribute'=>'EmiPlanStatus',
								
								'filter' => ['1'=>'Active', '0'=>'Inactive'],
								'format'=>'raw',    
								'value' => function($model, $key, $index)
								{   
									if($model->EmiPlanStatus == 1)
									{
										return "<span style='color:green;'>Active</span>";
									}
									else
									{
									  return "<span style='color:red;'>Inactive</span>";
									}
								},
							],
							// 'IsDelete',
							// 'OnDate',
							// 'UpdatedDate',

							[
							'class' => 'yii\grid\ActionColumn',
							'headerOptions' => ['width' => '70'],
							'template' => '{update} {delete} {link}',
							],
						],
					]); ?>
            
                <?php Pjax::end(); ?>


        </div>


        <?php Panel::end() ?> 
    </div>
</div>



