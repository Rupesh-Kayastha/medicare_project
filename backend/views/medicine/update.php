<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Medicine */

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Medicine',
]) . ' #' . $model->Name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Medicines'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->MedicineId]];

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="medicine-update">

    <?= $this->render('_form', [
        'model' => $model,
		'Brands'=>$Brands,
    ]) ?>

</div>
