<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Hospital */

$this->title = Yii::t('backend', 'Create Hospital');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Hospitals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospital-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
