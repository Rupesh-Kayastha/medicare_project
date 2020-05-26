<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\Employee */

$this->title = Yii::t('company', 'View {modelClass}', [
    'modelClass' => 'Employee',
]) . ' #' . $model->EmployeeName;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="row">
    <div class="col-md-12">

        <?php         Panel::begin(
        [
        'header' => Html::encode($this->title),
        'icon' => 'users',
        ]
        )
         ?> 

        <div class="employee-view">


            <?= Html::a(Yii::t('company', 'Manage'), ['index'], ['class' => 'btn btn-warning btn-flat']) ?>
            <?= Html::a(Yii::t('company', 'Create'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
            <?= Html::a(Yii::t('company', 'Update'), ['update', 'id' => $model->EmployeeId], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('company', 'Delete'), ['delete', 'id' => $model->EmployeeId], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
            'confirm' => Yii::t('company', 'Are you sure you want to delete this item?'),
            'method' => 'post',
            ],
            ]) ?>

            <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                        'EmployeeId',
            'EmpId',
            'CompanyId',
            'Password',
            'OtpHash',
            'EmployeeRoleId',
            'EmployeeName',
            'Dob',
            'ContactNo',
            'EmailId:email',
            'BloodGroup',
            'IsDelete',
            'OnDate',
            'UpdatedDate',
            ],
            ]) ?>
        </div>

        <?php Panel::end() ?> 
    </div>
</div>


