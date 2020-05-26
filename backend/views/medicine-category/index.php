<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\tree\TreeView;
use common\models\MedicineCategory;
use \kartik\tree\Module;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MedicineCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Manage {modelClass}', [
    'modelClass' => 'Medicine Categories',
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


        <div class="medicine-category-index">
		<?php 
		echo TreeView::widget([
			'query' => MedicineCategory::find()->addOrderBy('root, lft'), 
			'nodeAddlViews' => [
				Module::VIEW_PART_1 => '@backend/views/medicine-category/basic',
				Module::VIEW_PART_2 => '@backend/views/medicine-category/additional'
			],
			'headingOptions' => ['label' => 'Categories'],
			'topRootAsHeading' => true, // this will override the headingOptions
			'rootOptions' => ['label'=>'<span class="text-primary">Categories</span>'],
			'fontAwesome' => true,     // optional
			'showInactive'=>true,
			'showIDAttribute'=>true,
			'iconEditSettings'=>['show'=>'none'],
			'allowNewRoots'=>false,
			'nodeTitle'=>'Category',
			'nodeTitlePlural'=>'Categories',
			'isAdmin' => false,        // optional (toggle to enable admin mode)
			'displayValue' => 0,        // initial display value
			'softDelete' => true,       // defaults to true
			'cacheSettings' => [        
				'enableCache' => true   // defaults to true
			],
			
		]);
		?>

        </div>


        <?php Panel::end() ?> 
    </div>
</div>



