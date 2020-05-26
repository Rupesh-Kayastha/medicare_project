<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model common\models\Medicine */

$this->title = Yii::t('backend', 'Import Medicine');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Medicines'), 'url' => ['importmedicines']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicine-create">

  <!--  <h3>Import File</h3> -->
  <?php $form = ActiveForm::begin(['action'=>['medicine/importmedicines']]); ?>
   <?= $form->field($model, 'MedicineId')->fileInput()->label("Import Medicine") ?>
    <?= Html::submitButton($model->isNewRecord ? 'Import' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    
<?php ActiveForm::end() ?>
</div>
