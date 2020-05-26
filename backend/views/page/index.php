<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Manage {modelClass}', [
    'modelClass' => 'Pages',
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


        <div class="page-index">


                <?php Pjax::begin(); ?>

            <div class="container">
                <div class="box-header with-border">
                    <div class="pull-left">
                        <?= Html::a(Yii::t('backend', 'Create Page'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
                    </div>
                </div>
            </div>

                            <?= \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover' => true,
                'filterModel' => $searchModel,
        'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                            'id',
            'title',
            'slug',
            'description',
            'keywords',
            // 'body:ntext',
            // 'status',
            // 'created_at',
            // 'updated_at',

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



