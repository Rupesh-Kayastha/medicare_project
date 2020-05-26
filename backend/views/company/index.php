<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Manage {modelClass}', [
    'modelClass' => 'Companies',
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


        <div class="company-index">


                <?php Pjax::begin(); ?>

            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('backend', 'Create Company'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                </div>
            </div>

                            <?= \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover' => true,
                'filterModel' => $searchModel,
        'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                            'CompanyId',
            'Name',
            'AddressLine1',
            'AddressLine2',
            'LandMark',
            // 'State',
            // 'City',
            // 'Zipcode',
            // 'ContactNo',
            // 'Logo:ntext',
            // 'ActiveStatus',
            // 'IsDelete',
            // 'OnDate',
            // 'UpdatedDate',

                [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '70'],
                'template' => '{view} {update}{link}',
                ],
                ],
                ]); ?>
            
                <?php Pjax::end(); ?>


        </div>


        <?php Panel::end() ?> 
    </div>
</div>



