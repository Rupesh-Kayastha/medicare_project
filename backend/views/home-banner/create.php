<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\HomeBanner */

$this->title = Yii::t('backend', 'Create Home Banner');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Home Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-banner-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
