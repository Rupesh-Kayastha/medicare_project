<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel company\models\search\EmiSchedulesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('company', '{modelClass}', [
    'modelClass' => 'Emi Schedules',
]);
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


        <div class="emi-schedules-index">

		<?php 
		
		$gridColumns=[
					// ['class' => 'yii\grid\SerialColumn'],
					[
						'attribute' => 'EmpId',
						'value'=>'employee.EmpId',
						'enableSorting'=>true,											
					],
					[
						'attribute' => 'EmployeeName',
						'value'=>'employee.EmployeeName',
						'enableSorting'=>true,											
					],				
					'OrderIdentifier',
					'EmiAmount',
					'EmiMonth',
					[
						'attribute'=>'EmiClearingStatus',
						'header'=>'EMI Clearing Status',
						'filter' => [0=>'Not Clear',1=>'Cleared'],
						'format'=>'raw',    
						'value' => function($model, $key, $index)
						{   
							if($model->EmiClearingStatus == 1)
							{
								return "Cleared";
							}
							else
							{
							  return "Not Clear";
							}
							
						},
					],
					[
						'attribute'=>'Action',
						'format'=>'raw',
						'content' => function($model,$key,$index, $column) {
							if($model->EmiAmount)
							{
								$val = "Confirm";
								$class = 'btn btn-success';
								return Html::button($val,$options = [
								'onclick'=>'addEMI('.$model->EmiAmount.','.$model->EmiSchedulesId.','.$model->EmployeeId.')',
								'class'=>$class,
							]);
							}
							
						}
					],
						
				];



		?>
                

            <div class="container">
                <div class="box-header with-border">
                    <?php echo $this->render('_search', ['dataProvider' => $dataProvider,'model' => $searchModel,'gridColumns'=>$gridColumns]); ?>
                </div>
            </div>
				<?php 
				echo ExportMenu::widget([
						'dataProvider' => $dataProvider,
						'columns' => $gridColumns,
						'showConfirmAlert'=>false,
						'filename'=>"EMI Schedules",
						'clearBuffers'=>true,
						'fontAwesome'=>true
					]);
					
				Pjax::begin();
				?>
                        <?= \yiister\gentelella\widgets\grid\GridView::widget([
							'dataProvider' => $dataProvider,
							'hover' => true,
							//'filterModel' => $searchModel,
							'columns' =>$gridColumns ,
						]); ?>
            
                <?php Pjax::end(); ?>


        </div>


        <?php Panel::end() ?> 
    </div>
</div>
<script type="text/javascript">
	function addEMI(EmiAmount,id,eid){
		// alert(id);
		
			$.ajax({
				type:'POST',
				url: "<?= Url::to(['emi-schedules/addemi'])?>",
				data:{'id':id,'eid':eid,'EmiAmount':EmiAmount},
				success: function (data) {
                console.log(data);
            },
				
				
			});
		
	
	}
</script>


