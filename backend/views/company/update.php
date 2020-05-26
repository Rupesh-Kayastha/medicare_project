<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Company',
]) . ' #' . $model->Name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Companies'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->CompanyId]];

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="company-update">

    <?= $this->render('_form', [
        'model' => $model,
		'empmodel'=>$empmodel
    ]) ?>

</div>
