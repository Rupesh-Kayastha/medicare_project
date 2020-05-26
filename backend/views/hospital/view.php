<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\Hospital */

$this->title = Yii::t('backend', 'View {modelClass}', [
    'modelClass' => 'Hospital',
]) . ' #' . $model->HospitalId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Hospitals'), 'url' => ['index']];
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

        <div class="hospital-view">


            <?= Html::a(Yii::t('backend', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->HospitalId], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->HospitalId], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
            'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
            'method' => 'post',
            ],
            ]) ?>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'HospitalId',
            'HospitalTypes',
            'HospitalName',
            'Phone_Number',
            'IsDelete',
            'OnDate',
            'UpdatedDate',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


