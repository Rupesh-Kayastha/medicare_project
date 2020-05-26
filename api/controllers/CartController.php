<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Cart;
use common\models\CartItem;
use common\models\TicketCartMap;
use common\models\Orders;
use common\models\OrderItems;
use common\models\TicketOrder;
use common\models\EmployeeAddress;
use common\models\Employee;
use common\models\EmiPlans;




class CartController extends BaseController
{
	public function actionPlaceorder(){
		$post = Yii::$app->request->post();
		extract($post);
		$order_instance = new Orders();
		$order_response = $order_instance->Placeorder($CartIdentifier);
		$json_return = array();
		$json_return['status'] = $order_response['status'];
		$json_return['msg'] = $order_response['msg'];
		$json_return['OrderIdentifier'] = $order_response['ord_idf'];
		$json_return['orderid'] = $order_response['ord_id'];
		
		return $this->asJson($json_return);
	}
	public function actionSetmontlysubscription(){
		
		$post = Yii::$app->request->post();
		extract($post);
		
		$Cart=Cart::find()->where(['EmployeeId'=>$employeeid,'OrderType'=>2,'CartStatus'=>0])->one();
		if($Cart){
			$Cart->MonthlySubscription = $montlysubscription;
			if($Cart->save()){
				$status = 1;
				if($Cart->MonthlySubscription)
				$msg = "Monthly Subscription Set Successfully.";
				else
				$msg = "Monthly Subscription Removed.";
			}else{
				$status = 0;
				$msg = "Unable To Process At This Moment";
			}
		}else{
			$status = 0;
			$msg = "Cart not available";
		}
		
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);
	}
	public function actionSetaddress(){
		//echo "inside function";
		$post = Yii::$app->request->post();
		extract($post);
		
		$Cart=Cart::find()->where(['EmployeeId'=>$employeeid,'OrderType'=>2,'CartStatus'=>0])->one();
		if($Cart){
			$Cart->AddressID = $addressid;
			if($Cart->save()){
				$status = 1;
				$msg = "Address set successfully..";
			}else{
				$status = 0;
				$msg = "Address not set..";
			}
		}else{
			$status = 0;
			$msg = "Cart not available";
		}
		
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);
	}
	public function actionSetpayment(){
		$post = Yii::$app->request->post();
		extract($post);
		$Cart=Cart::find()->where(['EmployeeId'=>$employeeid,'OrderType'=>2,'CartStatus'=>0])->one();
		if($Cart){
			$cartamount = $Cart->DiscountedTotalPrice;
			$Cart->PaymentType = $PaymentType;
			if($PaymentType==3){
				$Cart->EmiAmount = 0;
				$Cart->CreditBalanceUsed = $cartamount;
				$Cart->EmiPlanId = 0;
				$Cart->EmiPlanPeriod = 0;
			}else{
				$Cart->EmiPlanId = 0;
				$Cart->EmiPlanPeriod = 0;
				$Cart->EmiAmount = 0;
				$Cart->CreditBalanceUsed = 0;
			}
			if($Cart->save()){
				$status = 1;
				$msg = "Payment Type set successfully..";
			}else{
				$status = 0;
				$msg = "Payment Type not set..";
			}
		}
		else{
			$status = 0;
			$msg = "Cart not available";
		}
		
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);
	}
	public function actionSetemi(){
		$post = Yii::$app->request->post();
		extract($post);
		$Cart=Cart::find()->where(['EmployeeId'=>$employeeid,'OrderType'=>2,'CartStatus'=>0])->one();
		if($Cart){
			$cartamount = $Cart->RegularTotalPrice;
			$emidetails = EmiPlans::find()->where(['EmiPlanId'=>$emiplanid])->one();
			$emiplanperiod = $emidetails->EmiPlanPeriod;
			$emiamount = round($cartamount / $emiplanperiod,2);
			
			/*************** Data update *****************/
			$Cart->EmiPlanId = $emiplanid;
			$Cart->PaymentType = 4;
			$Cart->EmiPlanPeriod = $emiplanperiod;
			$Cart->EmiAmount = $emiamount;
			$Cart->CreditBalanceUsed = $cartamount;
			if($Cart->save()){
				$status = 1;
				$msg = "EMI set successfully..";
			}else{
				$status = 0;
				$msg = "EMI Type not set..";
			}
		}
		else{
			$status = 0;
			$msg = "Cart not available";
		}
		
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);	
	}

   public function actionCheckouthelper(){
	   $post=Yii::$app->request->post();
	   extract($post);
	   $address=array();
	   $emiplan=array();
	   $data=array();
	   $payment_method=array();
	   
	   $payment_method[1]=array("PaymentType"=>1,"PaymentTypeName"=>'COD(Self)',"PaymentTypeStatus"=>1);
	   //$payment_method[2]=array("PaymentType"=>2,"PaymentTypeName"=>'Online(Self)',"PaymentTypeStatus"=>1);
	   $payment_method[3]=array("PaymentType"=>3,"PaymentTypeName"=>'Direct-Debit(Company)',"PaymentTypeStatus"=>0);
	   $payment_method[4]=array("PaymentType"=>4,"PaymentTypeName"=>'EMI(Company)',"PaymentTypeStatus"=>0);
	   $resaddrs=EmployeeAddress::find()->where(['EmployeeId'=>$employeeid,'IsDelete'=>0])->orderBy(['OnDate'=>SORT_DESC])->all();
        
        
        if(count($resaddrs)>0)
        {
            foreach($resaddrs as $key=>$value)
            {
                $address[$key]['EmployeeAddressId']=$value->EmployeeAddressId;
                $address[$key]['AddressLine1']=urldecode($value->AddressLine1);
                $address[$key]['AddressLine2']=urldecode($value->AddressLine2);
                $address[$key]['LandMark']=urldecode($value->LandMark);
                $address[$key]['State']=$value->State;
                $address[$key]['City']=$value->City;
                $address[$key]['Zipcode']=$value->Zipcode;
                $address[$key]['ContactNo']=$value->ContactNo;
                
            }
        }
		$data['Addresses']=$address;
		
		$Employee=Employee::find()->where(['EmployeeId'=>$employeeid,'IsDelete'=>0,'EmployeeActiveStatus'=>1])->one();
		$Cart=Cart::find()->where(['EmployeeId'=>$employeeid,'OrderType'=>2,'CartStatus'=>0])->one();
		$CreditBalance = $Employee->CreditBalance;
		
		if($CreditBalance >= $Cart->RegularTotalPrice)
		{
			
			$CompanyEMI = $Employee->company->companyemiplan;
			if($CompanyEMI){
				
				$payment_method[3]["PaymentTypeStatus"] = 1;
				if(!$Cart->MonthlySubscription)
				$payment_method[4]["PaymentTypeStatus"] = 1;
				
				
				foreach($CompanyEMI as $value){
					if(($value->EmiPlanOrderMinAmount <= $Cart->RegularTotalPrice) && ($value->EmiPlanStatus == 1))
					{
						$status_emiplan = 1;
						$status_emiplan_show = "Enable";
						
					}else{
						$status_emiplan = 0;
						$status_emiplan_show = "Disable";
					}
					$emiplan[$value->EmiPlanId]['Status'] = $status_emiplan;
					$emiplan[$value->EmiPlanId]['EmiPlanName'] = $value->EmiPlanName;
					$emiplan[$value->EmiPlanId]['EmiPlanPeriod'] = $value->EmiPlanPeriod;
					$emiplan[$value->EmiPlanId]['EmiPlanOrderMinAmount'] = $value->EmiPlanOrderMinAmount;
					$emiplan[$value->EmiPlanId]['EmiPlanId'] =$value->EmiPlanId;
					$emiplan[$value->EmiPlanId]['EmiAmount'] =round($Cart->RegularTotalPrice/$value->EmiPlanPeriod,2);
					
					
					
				}
				$data['Emi']=$emiplan;
			}
			
		}
		else if($CreditBalance >= $Cart->DiscountedTotalPrice)
		{
			$payment_method[3]["PaymentTypeStatus"] = 1;
		}
		$data['PaymentMethods']=$payment_method;
		$data['CreditBalance']=$CreditBalance;
		
		$data['RegularTotalPrice']=$Cart->RegularTotalPrice;
		$data['DiscountedTotalPrice']=$Cart->DiscountedTotalPrice;
		$data['CartIdentifire']=$Cart->CartIdentifire;
		
		
		$results['status']=1;
        $results['message']="Data Fetched";
        $results['data']=$data;
        
        $this->sendResponce($results);
		
	   
   }
   public function actionCartdetail()
   {
      $results=array();
      $data=array();
      
      $arr=array();
      
      $employeeid=Yii::$app->request->post('Employeeid');
      $cartidentifire=Yii::$app->request->post('CartIdentifire');
      if($employeeid!='')
      {
        $res=Cart::find()->where(['EmployeeId'=>$employeeid,'OrderType'=>2,'CartStatus'=>0])->one();
      }
      else if($cartidentifire!='')
      {
        $res=Cart::find()->where(['CartIdentifire'=>$cartidentifire,'CartStatus'=>0])->one();
      }
      $payment_deatils = array('0'=>'Not Set','1'=>'COD(Self)','2'=>'Online(Self)','3'=>'Direct-Debit(Company)','4'=>'EMI(Company)');
	  $order_type=array(0=>'Not Set', 1=>'Order By Support',2=>'Order By Self');
      if(!empty($res))
      {
            
              $data['CartIdentifire']=$res->CartIdentifire;
              $data['EmployeeId']=$res->EmployeeId;
			  $data['EmpId']=$res->employee->EmpId;
			  $data['EmployeeName']=$res->employee->EmployeeName;
			  $data['Company']=$res->employee->company->Name;
              $data['RegularTotalPrice']=$res->RegularTotalPrice;
              $data['DiscountedTotalPrice']=$res->DiscountedTotalPrice;
              $data['PaymentType']=$res->PaymentType;
			  $data['PaymentMethod']=$payment_deatils[$res->PaymentType];
			  $data['CreditBalanceUsed']=$res->CreditBalanceUsed;
              $data['OrderType']= $order_type[$res->OrderType];
              
			  
			  if($res->AddressID){
			  $address['AddressLine1']=$res->deliveryaddress->AddressLine1;
			  $address['AddressLine2']=$res->deliveryaddress->AddressLine2;
			  $address['LandMark']=$res->deliveryaddress->LandMark;
			  $address['City']=$res->deliveryaddress->City;
			  $address['State']=$res->deliveryaddress->State;
			  $address['Zipcode']=$res->deliveryaddress->Zipcode;
			  $address['ContactNo']=$res->deliveryaddress->ContactNo;
			  $data['address']=$address;
			  }
			  else{
				 $data['address']=[]; 
			  }
			  
			  $EMI['EmiPlanId']=$res->EmiPlanId;
			  $EMI['EmiPlanName']=$res->EmiPlanId?$res->emiplan->EmiPlanName:"N/A";
			  $EMI['EmiPlanPeriod']=$res->EmiPlanId?$res->EmiPlanPeriod:"N/A";
			  $EMI['EmiAmount']=$res->EmiPlanId?$res->EmiAmount:"N/A";
			  
			  if($res->PaymentType==3){
				  $EMI['EmiAmount']=$res->DiscountedTotalPrice;
			  }
			  
			  
			  
			  $data['EMI']=$EMI;
              
             
				$data['item']=[];  
                foreach($res->cartItems as $k=>$v)
                {
                 
                  $arr['CartItemId']=$v->CartItemId;
                  $arr['CartMedicineId']=$v->CartMedicineId;
                  $arr['CartItemName']=$v->CartItemName;
                  $arr['CartItemQty']=$v->CartItemQty;
                  $arr['CartItemRegularPrice']=$v->CartItemRegularPrice;
                  $arr['CartItemDiscountedPrice']=$v->CartItemDiscountedPrice;
                  $arr['CartItemRegularRowTotal']=$v->CartItemRegularRowTotal;
                  $arr['CartItemDiscountedRowTotal']=$v->CartItemDiscountedRowTotal;
                  $arr['IsPrescription']=$v->IsPrescription;
                  $data['item'][]=$arr;
                 
                }
				
				if(count($data['item'])){
					$status=1;
					$data['MonthlySubscription']= $res->MonthlySubscription;
					$msg=count($data['item']).' item(s) in your cart.';
				}
				else{
					$status=0;
					$data['MonthlySubscription']=0;
					$res->MonthlySubscription=0;
					$res->save();
					$msg='Cart is Empty';
				}
            
            
           
      }
      else
      {
            $status=0;
            $msg='Cart is Empty.';
      }
      
        $results['status']=$status;
        $results['message']=$msg;
        $results['cartdetail']=$data;
        
        $this->sendResponce($results);
      
   }
   
   
   
   
   public function actionRevieworderconfirm(){
	   
		$post = Yii::$app->request->post();
		$carttoken = $post['CartIdentifire'];
		
		$TicketCartMap = TicketCartMap::find()->where(['CartIdentifire'=>$carttoken])->one();
		if($TicketCartMap){
			$Cart = $TicketCartMap->cartIdentifire;
			if($Cart){
				$Cart->CartStatus = 1;
				if($Cart->save()){
					$checkOrder = Orders::find()->where(['CartIdentifire'=>$carttoken])->one();
					if(!$checkOrder){
						$neworder = new Orders();
						$neworder->CartIdentifire = $Cart->CartIdentifire;
						$order_obj = new Orders();
						$neworder->OrderIdentifier = $order_obj->getOrderidentifier();
						$neworder->EmployeeId = $Cart->EmployeeId;
						$neworder->CompanyId = $Cart->employee->CompanyId;
						$neworder->PaymentType = $Cart->PaymentType;
						$neworder->DeliveryAddressID = $Cart->AddressID;
						$neworder->OrderStatus = 0;
						if($Cart->PaymentType!=4){
							$neworder->OrderTotalPrice = $Cart->DiscountedTotalPrice;
						}else{
							$neworder->OrderTotalPrice = $Cart->RegularTotalPrice;
						}
						$neworder->CreditBalanceUsed=$Cart->CreditBalanceUsed;
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
							/*TicketOrder Close*/
							$ticketorder = TicketOrder::find()->where(['Token'=>$TicketCartMap->TicketOrderToken])->one();
							if($ticketorder){
								$ticketorder->TicketStatus = 2;
								$ticketorder->save();
							}
							
							
							$results['status']=1;
							$results['orderid']=$neworder->OrderId;
							$results['OrderIdentifier']=$neworder->OrderIdentifier;
							$results['message']="Order confirm Successfully";
						}else{
							$results['status']=0;
							$results['orderid']=false;
							$results['OrderIdentifier']=false;
							$results['message']="Order not Placed due to some error.";
						}
					}else{
						
						$results['status']=1;
						$results['orderid']=false;
						$results['OrderIdentifier']=false;
						$results['message']="Order already placed.";
					}
				}else{
					$results['status']=0;
					$results['orderid']=false;
					$results['OrderIdentifier']=false;
					$results['message']="Cart not converted to order.";
					
				}
			}else{
				$results['status']=0;
				$results['orderid']=false;
				$results['OrderIdentifier']=false;
				$results['message']="Cart data not available.";				
			}
			
		}else{
			$results['status']=0;
			$results['orderid']=false;
			$results['OrderIdentifier']=false;
			$results['message']="No data found on for this order review";
			
		}
		
		$this->sendResponce($results);
	   
   }
   public function getCartidentifier($employeeid){
		
		$checkemployeecart = Cart::find()->where(['EmployeeId'=>$employeeid,'CartStatus'=>0,'OrderType'=>2])->one();
		if($checkemployeecart){
			$CartIdentifire = $checkemployeecart->CartIdentifire;
			
		}else{
			$Cart = new Cart();
			$count=$Cart::find()->count()+1;
			$CartIdentifire='AMD_CART'.str_pad($count,9,"0",STR_PAD_LEFT);
			
		}
			
			
		
		return $CartIdentifire;
	}
   
   
   public function actionAdditem(){
		$Cart = new Cart();
		$post = Yii::$app->request->post();
		extract($post);
		$CartIdentifire = $this->getCartidentifier($employeeid);
		$Cartcheck = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		if(!$Cartcheck){
			$Cart->CartIdentifire = $CartIdentifire;
			$Cart->EmployeeId = $employeeid;
			$Cart->OrderType = 2;
			$Cart->save();
			
		}
		
		$cartadded_status = $Cart->addItem($CartIdentifire,$medicineid);
		
		$cart_updatedata = $Cart->getCartitemdetails($CartIdentifire,$medicineid);
		
		$json_result = array();
		$json_result['status'] = $cartadded_status;
		$json_result['cart_updatedata'] = $cart_updatedata;
			
		
		return $this->asJson($json_result);
	}
	public function actionUpdatequantity(){
		$post = Yii::$app->request->post();
		$Cart_obj = new Cart();
		extract($post);
		$CartIdentifire = $this->getCartidentifier($employeeid);
		$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		$qty = -1;
		$status = $Cart->updateItem($CartIdentifire,$medicineid,$qty);
			
		$checkcart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		
		$cart_updatedata = $Cart->getCartitemdetails($CartIdentifire,$medicineid);
		$json_result = array();
		$json_result['status'] = $status;
		$json_result['cart_updatedata'] = $cart_updatedata;
		return $this->asJson($json_result);
   
	}
	public function actionRemoveitem(){
		
		$post = Yii::$app->request->post();
		$cartitemdetail = array();
		extract($post);
		$Cart_obj = new Cart();
		$CartIdentifire = $this->getCartidentifier($employeeid);
		$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		$status = $Cart->removeItem($CartIdentifire,$medicineid);			
		$updatecart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		if($updatecart){
			$cartitemdetail['RegularTotalPrice'] = $updatecart->RegularTotalPrice;
			$cartitemdetail['DiscountedTotalPrice'] = $updatecart->DiscountedTotalPrice;
		}else{
			$cartitemdetail['RegularTotalPrice'] = 0;
			$cartitemdetail['DiscountedTotalPrice'] = 0;
		}
		$json_result = array();
		$json_result['status'] = $status;
		$json_result['cart_updatedata'] = $cartitemdetail;
		
		return $this->asJson($json_result);
	}
}
?>