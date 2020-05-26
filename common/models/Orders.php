<?php

namespace common\models;

use Yii;
use common\models\search\OrdersSearch;
use frontend\models\MedicineCategory;
use common\models\MedicineCategoryMaping;
use common\models\Cart;
use common\models\TicketOrder;
use common\models\TicketChat;
use common\models\CartItem;
use common\models\TicketCartMap;
use common\models\EmployeeAddress;
use common\models\Employee;
use common\models\EmiPlans;
use common\models\Orders;
use common\models\OrderItems;
use frontend\models\LoginForm;
use common\models\Company;
use backend\models\User;

/**
 * This is the model class for table "{{%Orders}}".
 *
 * @property int $OrderId
 * @property string $CartIdentifire
 * @property int $EmployeeId
 * @property double $OrderTotalPrice
 * @property int $PaymentType 1->COD,2->Online,3->Direct,4->EMI
 * @property int $DeliveryAddressID
 * @property int $EmiPlanId
 * @property int $EmiPlanPeriod
 * @property double $EmiAmount
 * @property double $CreditBalanceUsed
 * @property int $OrderType 0->Not Set, 1->Order By Support,2->Order By Self
 * @property string $CreatedDate
 * @property string $UpdatedDate
 * @property int $IsDelete
 *
 * @property Cart $cartIdentifire
 * @property Employee $employee
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%Orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CartIdentifire', 'EmployeeId','CompanyId'], 'required'],
            [['EmployeeId', 'PaymentType','MonthlySubscription','SubscriptionStatus','DeliveryAddressID', 'EmiPlanId', 'EmiPlanPeriod', 'OrderType', 'IsDelete'], 'integer'],
            [['OrderTotalPrice', 'EmiAmount', 'CreditBalanceUsed'], 'number'],
            [['MonthlySubscription','ParentOrderIdentifier','SubscriptionStatus','CreatedDate','ConfirmDate', 'UpdatedDate'], 'safe'],
            [['CartIdentifire','ParentOrderIdentifier'], 'string', 'max' => 100],
            [['CartIdentifire'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::className(), 'targetAttribute' => ['CartIdentifire' => 'CartIdentifire']],
            [['EmployeeId'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['EmployeeId' => 'EmployeeId']],
			[['CompanyId'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['CompanyId' => 'CompanyId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'OrderId' => 'Order ID',
            'CartIdentifire' => 'Cart Identifire',
            'OrderIdentifier' => 'Order-ID',
            'EmployeeId' => 'Employee Name',
			'CompanyId'=>'Company ID',
            'OrderTotalPrice' => 'Order Total Price',
            'PaymentType' => 'Payment Type',
			'MonthlySubscription'=>'Monthly Subscription',
			'SubscriptionStatus'=>'Subscription Status',
			'ParentOrderIdentifier'=>'Parent Order',
            'DeliveryAddressID' => 'Delivery Address ID',
            'EmiPlanId' => 'Emi Plan ID',
            'EmiPlanPeriod' => 'Emi Plan Period',
            'EmiAmount' => 'Emi Amount',
            'CreditBalanceUsed' => 'Credit Balance Used',
            'OrderType' => 'Order Type',
            'CreatedDate' => 'Created Date',
			'ConfirmDate' => 'Order Confirm Date',
            'UpdatedDate' => 'Updated Date', 
            'IsDelete' => 'IsDelete',
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
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['EmployeeId' => 'EmployeeId']);
    }
    
    public function getEmployeeaddress()
    {
        return $this->hasOne(EmployeeAddress::className(), ['EmployeeAddressId' => 'DeliveryAddressID']);
    }
    
    public function getOrderitems()
    {
        return $this->hasMany(OrderItems::className(), ['OrdersID' => 'OrderId']);
    }
	
	public function getEmiplan()
    {
        return $this->hasOne(EmiPlans::className(), ['EmiPlanId' => 'EmiPlanId']);
    }
	public function getCompany()
    {
        return $this->hasOne(Company::className(), ['CompanyId' => 'CompanyId']);
    }
    
    /**
     * {@inheritdoc}
     * @return \common\models\active\OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\OrdersQuery(get_called_class());
    }
	
	/****************** Custom Function *********************/
	public function getOrderidentifier(){
		$Orders = new Orders();
		$count = $Orders::find()->count()+1;
		$OrderIdentifier='AMD_ORDER'.str_pad($count,9,"0",STR_PAD_LEFT);
		return $OrderIdentifier;
	}
	
	public function Placeorder($CartIdentifier){
		$result = array();
		if($CartIdentifier!=''){
			$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifier])->one();
			if($Cart){
				$Cart->CartStatus = 1;
				if($Cart->save()){
					$checkOrder = Orders::find()->where(['CartIdentifire'=>$CartIdentifier])->one();
					if(!$checkOrder){
						$neworder = new Orders();
						$order_obj = new Orders();
						$OrderIdentifier= $order_obj->getOrderidentifier();
						$neworder->OrderIdentifier =$OrderIdentifier;
						$neworder->CartIdentifire = $CartIdentifier;
						$neworder->EmployeeId = $Cart->EmployeeId;
						$neworder->CompanyId = $Cart->employee->CompanyId;
						$neworder->PaymentType = $Cart->PaymentType;
						$neworder->MonthlySubscription =$Cart->MonthlySubscription;
						$neworder->ParentOrderIdentifier = $OrderIdentifier;
						$neworder->SubscriptionStatus = 1;
						$neworder->DeliveryAddressID = $Cart->AddressID;
						$neworder->OrderStatus = 0;
						if($Cart->PaymentType!=4){
							$neworder->OrderTotalPrice = $Cart->DiscountedTotalPrice;
						}else{
							$neworder->OrderTotalPrice = $Cart->RegularTotalPrice;
						}
						$neworder->CreditBalanceUsed = $Cart->CreditBalanceUsed;
						
						$neworder->EmiPlanId = $Cart->EmiPlanId;
						$neworder->EmiPlanPeriod = $Cart->EmiPlanPeriod;
						$neworder->EmiAmount = $Cart->EmiAmount;
						
						if($Cart->PaymentType==3){
							
							$neworder->EmiAmount = $Cart->DiscountedTotalPrice;
						}
						
						
						$neworder->OrderType = $Cart->OrderType;
												
						if($neworder->save()){
							$CartItems = $Cart->cartItems;
							if($CartItems){
								foreach($CartItems as $keyitm => $valueitm){
									$orderitem_create = new OrderItems();
									$orderitem_create->CartIdentifire = $valueitm->CartIdentifire;
									$orderitem_create->OrdersID = $neworder->OrderId;
									$orderitem_create->OrderMedicineID = $valueitm->CartMedicineId;
									$orderitem_create->OrderItemName = $valueitm->CartItemName;
									$orderitem_create->OrderItemQty = $valueitm->CartItemQty;
									if($Cart->PaymentType!=4){
										$OrderItemPrice = $valueitm->CartItemDiscountedPrice;
										$OrderItemTotalPrice = $valueitm->CartItemDiscountedRowTotal;
									}else{
										$OrderItemPrice = $valueitm->CartItemRegularPrice;
										$OrderItemTotalPrice = $valueitm->CartItemRegularRowTotal;
									}
									$orderitem_create->OrderItemPrice = $OrderItemPrice;
									$orderitem_create->OrderItemTotalPrice = $OrderItemTotalPrice;
									$orderitem_create->IsPrescription = $valueitm->IsPrescription;
									$orderitem_create->save();
									
								}
							}
							/********Employe Balance update*******/
							$employee = $Cart->employee;
							if($employee){
								$CreditBalance = $employee->CreditBalance;
								if($Cart->PaymentType == 4 || $Cart->PaymentType == 3){
									$updatebalance = $CreditBalance - $neworder->OrderTotalPrice;
									$employee->CreditBalance = $updatebalance;
									$employee->CreditOnHold = $employee->CreditOnHold + $neworder->OrderTotalPrice;
									
								}
								
								$employee->save();
							}
							if(Yii::$app->session->has('CartIdentifire'))
							Yii::$app->session->remove('CartIdentifire');
							$status = 1;
							$msg = "Order Placed Sucessfully.";
							$ord_idf=$neworder->OrderIdentifier;
							$ord_id=$neworder->OrderId;
							
						}else{
							$status = 0;
							$msg = "Order not Placed due to some error...";
							$ord_idf=0;
							$ord_id=0;
						}
					}else{
						$status = 0;
						$msg = "Order already placed...";
						
						$ord_idf=$checkOrder->OrderIdentifier;
						$ord_id=$checkOrder->OrderId;
					}
				}else{
					$status = 0;
					$msg = "Cart not converted to order ..";
					$ord_idf=0;
						$ord_id=0;
				}
			}else{
				$status = 0;
				$msg = "Cart data not available..";
				$ord_idf=0;
				$ord_id=0;
				
			}
		}else{
			
			$status = 0;
			$msg = "CartIdentifier not available..";
			$ord_idf=0;
			$ord_id=0;
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		$result['ord_idf'] = $ord_idf;
		$result['ord_id'] = $ord_id;
		return $result;
	}
	
	public function adminOrderapprove($OrderId){
		
		$result = array();
		if($OrderId!=''){
			$Order = Orders::find()->where(['OrderId'=>$OrderId,'OrderStatus'=>0])->one();
			if($Order){		
				/********Employe Balance update*******/
				$employee = $Order->employee;
				if($employee){
					$CreditBalance = $employee->CreditBalance;
					if($Order->PaymentType == 4 || $Order->PaymentType == 3){
						
		
						$employee->CreditOnHold =  $employee->CreditOnHold - $Order->CreditBalanceUsed;
						
					}
					if($employee->save()){
						$Order->OrderStatus = 1;
						$Order->ConfirmDate = date("Y-m-d");
						if($Order->save()){
							Yii::$app->session->setFlash('success', "Order Confirmed Successfully..");
							$status = 1;
							$msg = "Order Confirmed Successfully";
                             $ShipRocket =new ShipRocket();
        
                            if($ShipRocket->Connect()){
                                $data=$ShipRocket->PrepareOrder($Order->OrderIdentifier);
                               
                                   if($data)
                                       $ShipRocket->PlaceOrder($data);
                                
                                
                            }
						}else{
							Yii::$app->session->setFlash('error', "There is some error while update the Orders Data.");
							$status = 0;
							$msg = "There is some error while update the Orders Data.";
						}
					}
				}else{
					Yii::$app->session->setFlash('error', "Employee Details not available...");
					$status = 0;
					$msg = "Employee Details not available...";
				}
					
			}else{
				Yii::$app->session->setFlash('error', "Order ID not available...");
				$status = 0;
				$msg = "Order ID not available...";
			}
		}else{
			Yii::$app->session->setFlash('error', "OrderID is empty...");
			$status = 0;
			$msg = "OrderID is empty...";
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
	public function adminOrderreject($OrderId,$Comment){
		$result = array();
		if($OrderId!=''){
			$Order = Orders::find()->where(['OrderId'=>$OrderId,'OrderStatus'=>0])->one();
			if($Order){					
				$Order->OrderStatus = 2;
				if(trim($Comment)!=''){
					$cmnt = $Comment;
				}else{
					$cmnt = "";
				}
				$Order->OrderComment = $cmnt;
				if($Order->save()){
					$employee = $Order->employee;
					if($employee){
						$CreditBalance = $employee->CreditBalance;
						if($Order->PaymentType == 4 || $Order->PaymentType == 3){
							$updatebalance = $CreditBalance + $Order->CreditBalanceUsed;
							$employee->CreditBalance = $updatebalance;
							$employee->CreditOnHold =  $employee->CreditOnHold - $Order->CreditBalanceUsed;
							
						}
						$employee->save();
					}
					Yii::$app->session->setFlash('success', "Order Reject Successfully...");
					$status = 1;
					$msg = "Order Reject Successfully";
				}else{
					Yii::$app->session->setFlash('error', "There is some error while update the Orders Data.");
					$status = 0;
					$msg = "There is some error while update the Orders Data.";
				}
			}else{
				Yii::$app->session->setFlash('error', "Order ID not available...");
				$status = 0;
				$msg = "Order ID not available...";
			}
		}else{
			Yii::$app->session->setFlash('error', "Order ID cannot be empty..");
			$status = 0;
			$msg = "Order ID cannot be empty..";
		}
		$result['status'] = $status;
		$result['msg'] = $msg;
		return $result;
	}
	
}
