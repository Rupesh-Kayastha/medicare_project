<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Company */

$this->title = Yii::t('backend', 'Create Company');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <?= $this->render('_form', [
        'model' => $model,
		'empmodel'=>$empmodel
    ]) ?>

</div>
