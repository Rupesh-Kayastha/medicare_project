<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "MedicineCategory".
 *
 * @property int $id
 * @property int $root
 * @property int $lft
 * @property int $rgt
 * @property int $lvl
 * @property string $name
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
 */
class MedicineCategory extends \kartik\tree\models\Tree
{
    /**
     * {@inheritdoc}
     */
	 
	public $encodeNodeNames = false;
	//public $allowNewRoots= false;
	
    public static function tableName()
    {
        return 'MedicineCategory';
    }
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['description', 'required'];
        $rules[] = ['IncludeInMenu', 'required'];
        $rules[] = ['image', 'safe'];
        return $rules;
    }
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMedicineCategoryMapings()
	{
	   return $this->hasMany(MedicineCategoryMaping::className(), ['MedicineCategoryId' => 'id']);
	}
	
	public static function JSON() {
        /** @var TreeQuery $query */
        $query = self::find()
            ->addOrderBy('root, lft')
            ->select(['id', 'active', 'name', 'selected', 'root', 'lft', 'rgt', 'lvl']);


        /** @var array|ActiveRecord[] $nodes */
        $nodes = $query->all();

        return $nodes;
    }
}
