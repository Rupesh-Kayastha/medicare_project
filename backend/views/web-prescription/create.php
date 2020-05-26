<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WebPrescription */

$this->title = Yii::t('backend', 'Create Web Prescription');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Web Prescriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-prescription-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
