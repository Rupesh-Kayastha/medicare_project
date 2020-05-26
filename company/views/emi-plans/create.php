<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\EmiPlans */

$this->title = Yii::t('company', 'Create Emi Plans');
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Emi Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emi-plans-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
