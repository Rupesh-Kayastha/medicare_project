<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Hospital */

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Hospital',
]) . ' #' . $model->HospitalId;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Hospitals'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->HospitalId, 'url' => ['view', 'id' => $model->HospitalId]];

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="hospital-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
