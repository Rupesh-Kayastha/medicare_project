<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = Yii::t('backend', 'View {modelClass}', [
    'modelClass' => 'Company',
]) . ' #' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Companies'), 'url' => ['index']];
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

        <div class="company-view">


            <?= Html::a(Yii::t('backend', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->CompanyId], ['class' => 'btn btn-primary btn-flat']) ?>
            

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'CompanyId',
            'Name',
            'AddressLine1',
            'AddressLine2',
            'LandMark',
            'State',
            'City',
            'Zipcode',
            'ContactNo',
            'Logo:ntext',
            'ActiveStatus',
            'IsDelete',
            'OnDate',
            'UpdatedDate',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


