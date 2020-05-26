<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EmiPlans */

$this->title = Yii::t('company', 'Update {modelClass}', [
    'modelClass' => 'Emi Plans',
]) . ' #' . $model->EmiPlanId;

$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Emi Plans'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->EmiPlanId, 'url' => ['view', 'id' => $model->EmiPlanId]];

$this->params['breadcrumbs'][] = Yii::t('company', 'Update');
?>
<div class="emi-plans-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
