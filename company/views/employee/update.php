<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Employee */

$this->title = Yii::t('company', 'Update {modelClass}', [
    'modelClass' => 'Employee',
]) . ' #' . $model->EmployeeName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Employees'), 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->EmployeeName, 'url' => ['view', 'id' => $model->EmployeeId]];

$this->params['breadcrumbs'][] = Yii::t('company', 'Update');
?>
<div class="employee-update">

    <?= $this->render('_form', [
        'model' => $model,
		'EmployeeRole'=>$EmployeeRole,
		
    ]) ?>

</div>
