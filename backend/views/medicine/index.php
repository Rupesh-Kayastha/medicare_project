<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\models\MedicineCategory;
use kartik\tree\TreeViewInput;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MedicineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Manage {modelClass}', [
    'modelClass' => 'Medicines',
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


        <div class="medicine-index">


                <?php Pjax::begin(); ?>
				
            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('backend', 'Create Medicine'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                </div>
            </div>

                            <?= \yiister\gentelella\widgets\grid\GridView::widget([
									'dataProvider' => $dataProvider,
									'hover' => true,
									'filterModel' => $searchModel,
									'columns' => [
										//['class' => 'yii\grid\SerialColumn'],

										'MedicineId',
										'Name',
										[
											'attribute' => 'BrandName',
											'value'=>'brand.Name',
											'enableSorting'=>true,											
										],
										'MediceneImage',
										'RegularPrice',
										'DiscountedPrice',
										[
											'attribute'=>'IsPrescription',
											
											'filter' => ['1'=>'Yes', '0'=>'No'],
											'format'=>'raw',    
											'value' => function($model, $key, $index)
											{   
												if($model->IsPrescription == 1)
												{
													return "<span style='color:green;'>Yes</span>";
												}
												else
												{
												  return "<span style='color:red;'>No</span>";
												}
											},
										],
										[
											'attribute'=>'InStock',
											
											'filter' => ['1'=>'In Stock', '0'=>'Out of Stock'],
											'format'=>'raw',    
											'value' => function($model, $key, $index)
											{   
												if($model->InStock == 1)
												{
													return "<span style='color:green;'>In Stock</span>";
												}
												else
												{
												  return "<span style='color:red;'>Out of Stock</span>";
												}
											},
										],
										[
											'attribute'=>'BestSeller',
											
											'filter' => ['1'=>'Active', '0'=>'Inactive'],
											'format'=>'raw',    
											'value' => function($model, $key, $index)
											{   
												if($model->BestSeller == 1)
												{
													return "<span style='color:green;'>Active</span>";
												}
												else
												{
												  return "<span style='color:red;'>Inactive</span>";
												}
											},
										],
										[
										'class' => 'yii\grid\ActionColumn',
										'headerOptions' => ['width' => '70'],
										'template' => '{view} {update} {delete} {link}',
										],
									],
								]); 
							?>
            
                <?php Pjax::end(); ?>


        </div>


        <?php Panel::end() ?> 
    </div>
</div>



