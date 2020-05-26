<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Orders',
]) . ' #' . $model->OrderId;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Orders'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->OrderId, 'url' => ['view', 'id' => $model->OrderId]];

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="orders-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
