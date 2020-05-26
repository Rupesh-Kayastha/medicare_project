<?php

namespace api\models;

use Yii;
use common\models\MedicineCategoryMaping;
use creocoder\nestedsets\NestedSetsBehavior;
use api\models\query\MenuQuery;
/**
 * This is the model class for table "{{%MedicineCategory}}".
 *
 * @property int $id
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
 * @property string $description
 * @property string $image
 * @property int $IncludeInMenu
 * @property string $icon
 * @property int $icon_type
 * @property int $active
 * @property int $selected
 * @property int $disabled
 * @property int $readonly
 * @property int $visible
 * @property int $collapsed
 * @property int $movable_u
 * @property int $movable_d
 * @property int $movable_l
 * @property int $movable_r
 * @property int $removable
 * @property int $removable_all
 * @property int $child_allowed
 *
 * @property MedicineCategoryMaping[] $medicineCategoryMapings
 */
class MedicineCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%MedicineCategory}}';
    }

   public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'root',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'lvl',
            ],
        ];
    }

   
	public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new MenuQuery(get_called_class());
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedicineCategoryMapings()
    {
        return $this->hasMany(MedicineCategoryMaping::className(), ['MedicineCategoryId' => 'id']);
    }
	
	
	private static function getTreeInline($categories, $left = 0, $right = null, $lvl = 1){

		$names=[];

		foreach ($categories as $index => $category) {
			
			if ($category->lft >= $left + 1 && (is_null($right) || $category->rgt <= $right) && $category->lvl == $lvl) {
				
				if($category->IncludeInMenu && $category->active){
					
					$node=[];
					
					
					$childs = self::getTreeInline($categories, $category->lft, $category->rgt, $category->lvl + 1);
					
					
						$node['category_id']=$category->id;
						$node['lvl'] = $category->lvl;
						$node['label']=$category->name;
						$node['items'] = $childs;
						
						
					
					$names[]= $node;
				}
			}
		}
		return $names;

	}


	public static function getFullTreeInline(){

		$roots = MedicineCategory::find()->roots()->addOrderBy('root, lft')->all();
		$numRoots = count($roots);
		$i = 0;
		$tree = [];
		$last =false;
		foreach ($roots as $root){
			
			if($root->IncludeInMenu && $root->active){
				$childs=self::getTreeInline($root->children()->all());
				
					$RootNode=[];
					$RootNode=[
						'category_id' => $root->id,
						'label' => $root->name,
						'items'=>$childs,
						'lvl'=>$root->lvl
					];
					
					$tree [] = $RootNode;
				
				
				
				
			}
		}
		if(isset($tree[0]) && isset($tree[0]['items']))
		return $tree[0]['items'];
		else
		return [];
	}
	
	
}
