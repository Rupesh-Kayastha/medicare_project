<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\HomeBanner */

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Home Banner',
]) . ' #' . $model->BannerId;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Home Banners'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->BannerId, 'url' => ['view', 'id' => $model->BannerId]];

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="home-banner-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
