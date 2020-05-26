<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\HomeBanner */

$this->title = Yii::t('backend', 'View {modelClass}', [
    'modelClass' => 'Home Banner',
]) . ' #' . $model->BannerId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Home Banners'), 'url' => ['index']];
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

        <div class="home-banner-view">


            <?= Html::a(Yii::t('backend', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->BannerId], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->BannerId], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
            'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
            'method' => 'post',
            ],
            ]) ?>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'BannerId',
            'BannerImage',
            'WebLink',
            'IsDelete',
            'OnDate',
            'UpdatedDate',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


