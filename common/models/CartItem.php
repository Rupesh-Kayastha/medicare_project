<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%CartItem}}".
 *
 * @property int $CartItemId
 * @property string $CartIdentifire
 * @property int $CartMedicineId
 * @property string $CartItemName
 * @property int $CartItemQty
 * @property double $CartItemRegularPrice
 * @property double $CartItemDiscountedPrice
 * @property double $CartItemRegularRowTotal
 * @property double $CartItemDiscountedRowTotal
 * @property int $IsPrescription
 *
 * @property Cart $cartIdentifire
 * @property Medicine $cartMedicine
 */
class CartItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%CartItem}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CartIdentifire', 'CartMedicineId', 'CartItemName'], 'required'],
            [['CartMedicineId', 'CartItemQty', 'IsPrescription'], 'integer'],
            [['CartItemRegularPrice', 'CartItemDiscountedPrice', 'CartItemRegularRowTotal', 'CartItemDiscountedRowTotal'], 'number'],
            [['CartIdentifire'], 'string', 'max' => 100],
            [['CartItemName'], 'string', 'max' => 255],
            [['CartIdentifire'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::className(), 'targetAttribute' => ['CartIdentifire' => 'CartIdentifire']],
            [['CartMedicineId'], 'exist', 'skipOnError' => true, 'targetClass' => Medicine::className(), 'targetAttribute' => ['CartMedicineId' => 'MedicineId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CartItemId' => Yii::t('app', 'Cart Item ID'),
            'CartIdentifire' => Yii::t('app', 'Cart Identifire'),
            'CartMedicineId' => Yii::t('app', 'Cart Medicine ID'),
            'CartItemName' => Yii::t('app', 'Cart Item Name'),
            'CartItemQty' => Yii::t('app', 'Cart Item Qty'),
            'CartItemRegularPrice' => Yii::t('app', 'Cart Item Regular Price'),
            'CartItemDiscountedPrice' => Yii::t('app', 'Cart Item Discounted Price'),
            'CartItemRegularRowTotal' => Yii::t('app', 'Cart Item Regular Row Total'),
            'CartItemDiscountedRowTotal' => Yii::t('app', 'Cart Item Discounted Row Total'),
            'IsPrescription' => Yii::t('app', 'Is Prescription'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCartIdentifire()
    {
        return $this->hasOne(Cart::className(), ['CartIdentifire' => 'CartIdentifire']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCartMedicine()
    {
        return $this->hasOne(Medicine::className(), ['MedicineId' => 'CartMedicineId']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\CartItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\CartItemQuery(get_called_class());
    }
	
	public function calculateRowTotal(){
		
		$CartItemQty=$this->CartItemQty;
		$CartItemRegularPrice=$this->CartItemRegularPrice;
		$CartItemDiscountedPrice=$this->CartItemDiscountedPrice;
		$this->CartItemDiscountedRowTotal=$CartItemQty*$CartItemDiscountedPrice;
		$this->CartItemRegularRowTotal=$CartItemQty*$CartItemRegularPrice;
		$this->save();	
		
	}
}
