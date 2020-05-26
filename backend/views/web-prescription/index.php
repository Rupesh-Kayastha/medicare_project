<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WebPrescriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Manage {modelClass}', [
    'modelClass' => 'Web Prescriptions',
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


        <div class="web-prescription-index">

            <?php Pjax::begin(); ?>


            <?= \yiister\gentelella\widgets\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'hover' => true,
                'filterModel' => $searchModel,

                'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                    'Id',
                    'EmployeeId',
                    'UserName',
                    'UserContact',
                    'UserMail',
                    'UserMessage',
                    'UserAddress',
                    
                    [
                        'attribute' => 'Prescription', 
                        'format'=>'raw',
                        'value' => function ($model) {
                            if($model->Prescription)
                            {
                               return Html::a('View File', Yii::getAlias('@storageUrl').'/webprescription/'.$model->Prescription, ['class' => 'profile-link']);
                           }
                       }
                   ],

                   [
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['width' => '70'],
                    'template' => '{delete} {link}',
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?> 

    </div>


    <?php Panel::end() ?> 
</div>
</div>



