<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('company', 'Manage {modelClass}', [
    'modelClass' => 'Employees',
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


        <div class="employee-index">


                <?php Pjax::begin(); ?>

            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('company', 'Create Employee'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                </div>
            </div>

                            <?= \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover' => true,
                'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
			
					[
						'label' => Yii::t('company', 'Employee Id'),
						'attribute' => 'EmpId',
						
					],
					[
						'attribute' => 'EmployeeRole',
						'value'=>'employeeRole.EmployeeRole',
						'enableSorting'=>true,
					],
					'EmployeeName',
					// 'Dob',
					 'ContactNo',
					 'EmailId:email',
					// 'BloodGroup',
					// 'IsDelete',
					// 'OnDate',
					// 'UpdatedDate',

					[
					'class' => 'yii\grid\ActionColumn',
					'headerOptions' => ['width' => '70'],
					'template' => '{view} {update} {delete} {link}',
					],
                ],
                ]); ?>
            
                <?php Pjax::end(); ?>


        </div>


        <?php Panel::end() ?> 
    </div>
</div>



