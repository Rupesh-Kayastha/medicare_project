<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Medicine */

$this->title = Yii::t('backend', 'Create Medicine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Medicines'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicine-create">

    <?= $this->render('_form', [
        'model' => $model,
		'Brands'=>$Brands,
    ]) ?>

</div>
