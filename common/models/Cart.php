<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%Cart}}".
 *
 * @property int $CartId
 * @property string $CartIdentifire
 * @property int $EmployeeId
 * @property double $RegularTotalPrice
 * @property double $DiscountedTotalPrice
 *
 * @property Employee $employee
 * @property CartItem[] $cartItems
 * @property Orders[] $orders
 * @property TicketCartMap[] $ticketCartMaps
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%Cart}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['CartIdentifire', 'EmployeeId'], 'required'],
            [['CartIdentifire'], 'required'],
            [['EmployeeId'], 'integer'],
            [['RegularTotalPrice', 'DiscountedTotalPrice'], 'number'],
            [['CartIdentifire'], 'string', 'max' => 100],
            [['CartIdentifire'], 'unique'],
            //[['EmployeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EmployeeId' => 'EmployeeId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CartId' => Yii::t('app', 'Cart ID'),
            'CartIdentifire' => Yii::t('app', 'Cart Identifire'),
            'EmployeeId' => Yii::t('app', 'Employee ID'),
            'RegularTotalPrice' => Yii::t('app', 'Regular Total Price'),
            'DiscountedTotalPrice' => Yii::t('app', 'Discounted Total Price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['EmployeeId' => 'EmployeeId']);
    }
	
	public function getEmiplan()
    {
        return $this->hasOne(EmiPlans::className(), ['EmiPlanId' => 'EmiPlanId']);
    }
	
	public function getDeliveryaddress()
    {
        return $this->hasOne(EmployeeAddress::className(), ['EmployeeAddressId' => 'AddressID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCartItems()
    {
        return $this->hasMany(CartItem::className(), ['CartIdentifire' => 'CartIdentifire']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasOne(Orders::className(), ['CartIdentifire' => 'CartId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketCartMaps()
    {
        return $this->hasOne(TicketCartMap::className(), ['CartIdentifire' => 'CartIdentifire']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\CartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\CartQuery(get_called_class());
    }
	
	public function addItem($CartIdentifire,$medicineid){
		
		$CartItem=CartItem::find()->where(['CartIdentifire'=>$CartIdentifire,'CartMedicineId'=>$medicineid])->one();
		if($CartItem){
			$CartItem->CartItemQty= $CartItem->CartItemQty+1;
		}
		else{
			$Medicine=Medicine::find()->where(['MedicineId'=>$medicineid])->one();
			$CartItem=new CartItem();
			$CartItem->CartIdentifire=$CartIdentifire;
			$CartItem->CartMedicineId=$Medicine->MedicineId;
			$CartItem->CartItemName=$Medicine->Name;
			$CartItem->CartItemQty=1;
			$CartItem->CartItemRegularPrice=$Medicine->RegularPrice;
			$CartItem->CartItemDiscountedPrice=$Medicine->DiscountedPrice;			
			$CartItem->IsPrescription=$Medicine->IsPrescription;			
		}
		$CartItem->save();
		
		
		
		
		$CartItem->calculateRowTotal();
		$this->calculateTotal($CartIdentifire);
		$status = 1;
		return $status;
		
	}
	public function updateItem($CartIdentifire,$medicineid,$qty){
		
		$CartItem=CartItem::find()->where(['CartIdentifire'=>$CartIdentifire,'CartMedicineId'=>$medicineid])->one();
		if($CartItem){
			$CartItem->CartItemQty= $CartItem->CartItemQty+$qty;
			$CartItem->save();
			$CartItem->calculateRowTotal();
			
			if($CartItem->CartItemQty == 0){
			   $CartItem->delete();
			   
		    }
			
		}
		
		$this->calculateTotal($CartIdentifire);
		
		$status = 1;
		return $status;
		
		
	}
	
	public function removeItem($CartIdentifire,$medicineid){
		
		$CartItem=CartItem::find()->where(['CartIdentifire'=>$CartIdentifire,'CartMedicineId'=>$medicineid])->one();
		if($CartItem){
		
			$CartItem->delete();	
		}
		$this->calculateTotal($CartIdentifire);
		$status = 1;
		return $status;
	}
	
	public function calculateTotal($CartIdentifire){
		$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		if($Cart){
			$CartItems=$Cart->cartItems;
			
			$RegularTotalPrice=0;
			$DiscountedTotalPrice=0;
			foreach($CartItems as $CartItem){
				
				$RegularTotalPrice=$RegularTotalPrice+$CartItem->CartItemRegularRowTotal;
				$DiscountedTotalPrice=$DiscountedTotalPrice+$CartItem->CartItemDiscountedRowTotal;
				
			}
			$Cart->RegularTotalPrice=$RegularTotalPrice; 		
			$Cart->DiscountedTotalPrice=$DiscountedTotalPrice; 
			$Cart->save();
			if($Cart->RegularTotalPrice == 0 && $Cart->DiscountedTotalPrice == 0){
				$Cart->PaymentType = 0;
				$Cart->AddressID = 0;
				$Cart->EmiPlanId = 0;
				$Cart->EmiPlanPeriod = 0;
				$Cart->EmiAmount = 0;
				$Cart->CreditBalanceUsed = 0;
				$Cart->save();
			}
		}
	}
	
	
	
	public function getCartitemcount($CartIdentifire){
		if($CartIdentifire!=""){
			$check_CartIdentifire = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
			if($check_CartIdentifire){
				$cartitems = CartItem::find()->where(['CartIdentifire'=>$CartIdentifire])->all();
				$itemcount = count($cartitems);
			}else{
				$itemcount = 0;
			}
		}else{
			$itemcount = 0;
		}
		
		return $itemcount;
			
	}

	public function getCartitemdetails($CartIdentifire,$medicineid){
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		$cartitemdetail = array();
		if($Cart){
			$cartitemdetail['RegularTotalPrice'] = $Cart->RegularTotalPrice;
			$cartitemdetail['DiscountedTotalPrice'] = $Cart->DiscountedTotalPrice;
			$cartitem = CartItem::find()->where(['CartIdentifire'=>$CartIdentifire,'CartMedicineId'=>$medicineid])->one();
			if($cartitem){
				$cartitemdetail['CartItemQty'] = $cartitem->CartItemQty;
				$cartitemdetail['CartItemRegularRowTotal'] = $cartitem->CartItemRegularRowTotal;
				$cartitemdetail['CartItemDiscountedRowTotal'] = $cartitem->CartItemDiscountedRowTotal;
			}
		}
		
		return $cartitemdetail;
	}
	
	public function getHeadercartdetails($CartIdentifire){
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		$result = array();
		if($Cart){
			$Cartitems = CartItem::find()->where(['CartIdentifire'=>$CartIdentifire])->all();
			$Totalcartitem = count($Cartitems);
			$Totalamount = $Cart->RegularTotalPrice;
		}else{
			$Totalcartitem = 0;
			$Totalamount = 0;
		}
		$result['Totalcartitem'] = $Totalcartitem;
		$result['Totalamount'] = $Totalamount;
		return $result;
		
	}
	
	public function getCheckitem($CartIdentifire,$medicineid){
		$cartitem = CartItem::find()->where(['CartIdentifire'=>$CartIdentifire,'CartMedicineId'=>$medicineid])->one();
		if($cartitem){
			$status = 1;
		}else{
			$status = 0;
		}
		return $status;
	}
	
	public function updateloginItem($CartIdentifire,$medicineid,$qty){
		
		$CartItem=CartItem::find()->where(['CartIdentifire'=>$CartIdentifire,'CartMedicineId'=>$medicineid])->one();
		if($CartItem){
			$CartItem->CartItemQty= $CartItem->CartItemQty+$qty;
		}
		else{
			$Medicine=Medicine::find()->where(['MedicineId'=>$medicineid])->one();
			$CartItem=new CartItem();
			$CartItem->CartIdentifire=$CartIdentifire;
			$CartItem->CartMedicineId=$Medicine->MedicineId;
			$CartItem->CartItemName=$Medicine->Name;
			$CartItem->CartItemQty=$qty;
			$CartItem->CartItemRegularPrice=$Medicine->RegularPrice;
			$CartItem->CartItemDiscountedPrice=$Medicine->DiscountedPrice;			
			$CartItem->IsPrescription=$Medicine->IsPrescription;			
		}
		$CartItem->save();
		
		
		
		
		$CartItem->calculateRowTotal();
		$this->calculateTotal($CartIdentifire);
		$status = 1;
		return $status;
		
	}
	
	
}
