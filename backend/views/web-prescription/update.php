<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WebPrescription */

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Web Prescription',
]) . ' #' . $model->Id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Web Prescriptions'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->Id, 'url' => ['view', 'id' => $model->Id]];

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="web-prescription-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
