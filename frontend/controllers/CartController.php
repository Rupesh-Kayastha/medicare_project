<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
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
use common\models\Transaction;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;



class CartController extends Controller
{
	public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
	/************ function list *****************/
	public function getCartidentifier(){
		$session = Yii::$app->session;
        $session->open();
        if(Yii::$app->session->get('CartIdentifire')=='')
        {
			if(Yii::$app->user->identity !=''){
				$EmployeeId = Yii::$app->user->identity->EmployeeId;
				$checkemployeecart = Cart::find()->where(['EmployeeId'=>$EmployeeId,'CartStatus'=>0,'OrderType'=>2])->one();
				if($checkemployeecart){
					$CartIdentifire = $checkemployeecart->CartIdentifire;
					Yii::$app->session->set('CartIdentifire',$CartIdentifire);	
				}else{
				    $Cart = new Cart();
					$count=$Cart::find()->count()+1;
					$CartIdentifire='AMD_CART'.str_pad($count,9,"0",STR_PAD_LEFT);
					Yii::$app->session->set('CartIdentifire',$CartIdentifire);	
				}
			}else{
				$Cart = new Cart();
				$count=$Cart::find()->count()+1;
				$CartIdentifire='AMD_CART'.str_pad($count,9,"0",STR_PAD_LEFT);
				Yii::$app->session->set('CartIdentifire',$CartIdentifire);
			}
			
		}else{
			$CartIdentifire=Yii::$app->session->get('CartIdentifire');
		}
		return $CartIdentifire;
	}
	
	/***************** End function ***************************/
	
	public function actionAdditem(){
		$Cart = new Cart();
		$post = Yii::$app->request->post();
		extract($post);
		$CartIdentifire = $this->getCartidentifier();
		$Cartcheck = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		if(!$Cartcheck){
			$Cart->CartIdentifire = $CartIdentifire;
			if(isset(Yii::$app->user->identity->EmployeeId)){
				$Cart->EmployeeId = Yii::$app->user->identity->EmployeeId;
			}
			$Cart->OrderType = 2;
			$Cart->save();
			
		}
		
		$cartadded_status = $Cart->addItem($CartIdentifire,$medicineid);
		
		$cart_updatedata = $Cart->getCartitemdetails($CartIdentifire,$medicineid);
		
		$json_result = array();
		$json_result['status'] = $cartadded_status;
		$json_result['cart_updatedata'] = $cart_updatedata;
		$json_result['Headercartdetails'] = $Cart->getHeadercartdetails($CartIdentifire);
		
		//return json_encode($json_result);
		return $this->asJson($json_result);
	}
	
	public function actionUpdatequantity(){
		$post = Yii::$app->request->post();
		$Cart_obj = new Cart();
		extract($post);
		$CartIdentifire = $this->getCartidentifier();
		$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		$qty = -1;
		$status = $Cart->updateItem($CartIdentifire,$medicineid,$qty);
			
		$checkcart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		
		$cart_updatedata = $Cart->getCartitemdetails($CartIdentifire,$medicineid);
		$json_result = array();
		$json_result['status'] = $status;
		$json_result['CheckItem'] = $Cart_obj->getCheckitem($CartIdentifire,$medicineid);
		$json_result['cart_updatedata'] = $cart_updatedata;
		$json_result['Headercartdetails'] = $Cart_obj->getHeadercartdetails($CartIdentifire);
		
		
		return $this->asJson($json_result);
	}
	
	public function actionRemoveItem(){
		
		$post = Yii::$app->request->post();
		$cartitemdetail = array();
		extract($post);
		$Cart_obj = new Cart();
		$CartIdentifire = $this->getCartidentifier();
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
		$json_result['Headercartdetails'] = $Cart_obj->getHeadercartdetails($CartIdentifire);
		return $this->asJson($json_result);
	}
	
	public function actionViewcart(){
		$CartIdentifire = $this->getCartidentifier();
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		if($Cart){
			$CartItems = $Cart->cartItems;
			if($CartItems){
				$cartitems = array();
				$cartdetails = array();
				foreach($CartItems as $Cartitemkey=>$Cartitemvalu){
					$cartitems[$Cartitemkey]['CartItemName'] = $Cartitemvalu->CartItemName;
					$cartitems[$Cartitemkey]['CartItemQty'] = $Cartitemvalu->CartItemQty;
					$cartitems[$Cartitemkey]['CartMedicineId'] = $Cartitemvalu->CartMedicineId;
					$cartitems[$Cartitemkey]['CartItemRegularPrice'] = $Cartitemvalu->CartItemRegularPrice;
					$cartitems[$Cartitemkey]['CartItemDiscountedPrice'] = $Cartitemvalu->CartItemDiscountedPrice;
					$cartitems[$Cartitemkey]['CartItemRegularRowTotal'] = $Cartitemvalu->CartItemRegularRowTotal;
					$cartitems[$Cartitemkey]['CartItemDiscountedRowTotal'] = $Cartitemvalu->CartItemDiscountedRowTotal;
					if ($Cartitemvalu->cartMedicine->MediceneImage !='') {
						$getimage = $Cartitemvalu->cartMedicine->MediceneImage;
					}else{
						$getimage = Yii::getAlias('@storageUrl')."/default/medical.png";
					}
					$cartitems[$Cartitemkey]['CartItemImage'] = $getimage;
				}
				$cartdetails['CartIdentifire'] = $Cart->CartIdentifire;
				$cartdetails['CartId'] = $Cart->CartId;
				$cartdetails['RegularTotalPrice'] = $Cart->RegularTotalPrice;
				$cartdetails['DiscountedTotalPrice'] = $Cart->DiscountedTotalPrice;
				
				$CartItemmodel['CartItems'] = $cartitems;
				$CartItemmodel['CartDetails'] = $cartdetails;
			}else{
				$CartItemmodel['CartItems'] = array();
				$CartItemmodel['CartDetails'] = array();
			}
		}else{
			$CartItemmodel['CartItems'] = array();
			$CartItemmodel['CartDetails'] = array();
		}
		return $this->render('cart',['CartItem'=>$CartItemmodel]);
	}
	
	public function actionCheckout(){
		if(!Yii::$app->user->identity){
			return $this->redirect(['site/login']);
		}
		$CartIdentifier = $this->getCartidentifier();
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifier,'CartStatus'=>0])->one();
		if($Cart){
			if(Yii::$app->user->identity!=''){
				$second = "in";
				$display = "none";
				$empaddre = "block";
				$employeeid  = Yii::$app->user->identity->EmployeeId;
				$employeedetails = Employee::find()->where(['EmployeeId'=>$employeeid,'IsDelete'=>0])->one();
				$employeeaddress = $employeedetails->employeeAddresses;
			}else{
				$employeeaddress = "";
				$second = "";
				$display = "block";
				$empaddre = "none";
			}
			$model = new LoginForm();
			return $this->render('checkout',['employeeaddress'=>$employeeaddress,'second'=>$second,'display'=>$display,'companies' => ArrayHelper::map(Company::find()->where(['ActiveStatus'=>1,'IsDelete'=>0])->asArray()->all(), 'CompanyId', 'Name'),'model'=>$model,'empaddre'=>$empaddre]);
		}else{
			return $this->redirect(['viewcart']);
		}
	}
	
	public function actionAddnewaddress(){
		$post = Yii::$app->request->post();
		extract($post);
		if(Yii::$app->user->identity!=''){
			$employeeid = Yii::$app->user->identity->EmployeeId;
		}else{
			$employeeid = '';
		}
		
		$EmployeeAddress = new EmployeeAddress();
		$EmployeeAddress->EmployeeId = $employeeid;
		$EmployeeAddress->AddressLine1 = $addresline1;
		$EmployeeAddress->AddressLine2 = $addresline2;
		$EmployeeAddress->LandMark = $landmark;
		$EmployeeAddress->State = $state;
		$EmployeeAddress->City = $city;
		$EmployeeAddress->Zipcode = $postcode;
		$EmployeeAddress->ContactNo = $contactno;
		if($EmployeeAddress->save()){
			$alladdress = EmployeeAddress::find()->where(['EmployeeId'=>$employeeid])->all();
			$status = 1;
			$data = $alladdress ;
			$msg = "New Address added successfully..";
		}else{
			$status = 0;
			$data = "" ;
			$msg = "Employee address not added..";
		}
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		$json_array['address']=$data;
		return $this->asJson($json_array);
	}
	
	public function actionSetaddress(){
		//echo "inside function";
		$post = Yii::$app->request->post();
		extract($post);
		$CartIdentifire = $this->getCartidentifier();
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
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
		$CartIdentifire = $this->getCartidentifier();
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
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
			$Cart->save();
		}

		
	}
	public function actionPaymentinfo(){
	$EmployeeId = Yii::$app->user->identity->EmployeeId;
	$Employee=Employee::find()->where(['EmployeeId'=>$EmployeeId,'IsDelete'=>0,'EmployeeActiveStatus'=>1])->asArray()->one();
      echo json_encode($Employee);
	}
/*	public function actionPayment(){
		if (isset(Yii::$app->request->post()['continue']))
        {
            $empid=Yii::$app->request->post()['uid'];
            $empname=Yii::$app->request->post()['uname'];  
            $empnum=Yii::$app->request->post()['ucont'];           
            $empmail=Yii::$app->request->post()['uemail'];
            $amount=Yii::$app->request->post()['uamnt']; 
            $model=new Transaction(); 
            $model->EmpId=$empid;
            $model->UserName=$empname;
            $model->UserMob=$empnum;
            $model->UserMail=$empmail;
            $model->amount=$amount;              
            $model->save();
           return $this->render('payment',['empname' => $empname,'empnum' => $empnum,'empmail' => $empmail,'amount' => $amount]);
        }
        else{
        	echo "subimited by ".$_SERVER['REQUEST_METHOD'];
        }
		
	}*/
	public function actionSuccess(){
		return $this->render('checkout');
	}
	public function actionCartdetails(){
		$CartIdentifire = $this->getCartidentifier();
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		$payment_deatils = array('0'=>'Not Set','1'=>'COD(Self)','2'=>'Online(Self)','3'=>'Direct-Debit(Company)','4'=>'EMI(Company)');
		if($Cart){
			$CartItems = $Cart->cartItems;
			if($CartItems){
				$cartitems = array();
				$cartdetails = array();
				foreach($CartItems as $Cartitemkey=>$Cartitemvalu){
					$cartitems[$Cartitemkey]['CartItemName'] = $Cartitemvalu->CartItemName;
					$cartitems[$Cartitemkey]['CartItemQty'] = $Cartitemvalu->CartItemQty;
					$cartitems[$Cartitemkey]['CartMedicineId'] = $Cartitemvalu->CartMedicineId;
					if($Cart->PaymentType == 4){
						$cartitems[$Cartitemkey]['UnitPrice'] = $Cartitemvalu->CartItemRegularPrice;
						$cartitems[$Cartitemkey]['TotalRowPrice'] = $Cartitemvalu->CartItemRegularRowTotal;
					}else{
						$cartitems[$Cartitemkey]['UnitPrice'] = $Cartitemvalu->CartItemDiscountedPrice;
						$cartitems[$Cartitemkey]['TotalRowPrice'] = $Cartitemvalu->CartItemDiscountedRowTotal;
					}
					$cartitems[$Cartitemkey]['CartItemImage'] = Yii::getAlias('@storageUrl')."/default/medical.png";
				}
				$cartdetails['CartIdentifire'] = $Cart->CartIdentifire;
				$cartdetails['CartId'] = $Cart->CartId;
				if($Cart->PaymentType == 4){
					$cartdetails['GrandTotal'] = $Cart->RegularTotalPrice;
				}else{
					$cartdetails['GrandTotal'] = $Cart->DiscountedTotalPrice;
				}
				$cartdetails['PaymentType'] = $payment_deatils[$Cart->PaymentType];
				
				$CartItemmodel['CartItems'] = $cartitems;
				$CartItemmodel['CartDetails'] = $cartdetails;
				$CartItemmodel['CartAddress'] = $Cart->deliveryaddress;
				
				$status = 1;
				$msg ="Record found";
				$data = $CartItemmodel;
			}else{
				$CartItemmodel = "";
				$status = 0;
				$msg ="Record not found";
				$data = $CartItemmodel;	
			}
		}else{
			$CartItemmodel = "";
			$status = 0;
			$msg ="Record not found";
			$data = $CartItemmodel;
		}
		$json_data = array();
		$json_data['status'] = $status;
		$json_data['msg'] = $msg;
		$json_data['result'] = $data;
		return $this->asJson($json_data);
		
	}
	
	public function actionPlaceorder(){
		$post = Yii::$app->request->post();
		$CartIdentifier = $this->getCartidentifier();
		$order_instance = new Orders();
		$order_response = $order_instance->Placeorder($CartIdentifier);
		$json_return = array();
		$json_return['status'] = $order_response['status'];
		$json_return['msg'] = $order_response['msg'];
		return $this->asJson($json_return);
	}
	public function actionThankyou(){
		return $this->render('thank_you');
	}
	public function actionEmipayment(){
		$post = Yii::$app->request->post();
		extract($post);
		$emiplan_result = array();
		
		$CartIdentifire = $this->getCartidentifier();		
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire,'CartStatus'=>0])->one();
		$CartItemRegularPrice = $Cart->RegularTotalPrice;
		$Employee=Employee::find()->where(['EmployeeId'=>$Cart->EmployeeId,'IsDelete'=>0,'EmployeeActiveStatus'=>1])->one();
		$usercreditbalance = $Employee->CreditBalance;
		if($usercreditbalance >= $CartItemRegularPrice){

			$companyemi = $Employee->company->companyemiplan;
			if($companyemi){
				$key = 0;
				$j = 0;
				foreach($companyemi as $value){
					if(($value->EmiPlanOrderMinAmount <= $CartItemRegularPrice) && ($value->EmiPlanStatus == 1))
					{
						$status_emiplan = 1;
						$status_emiplan_show = "Enable";
						$j++;
					}else{
						$status_emiplan = 0;
						$status_emiplan_show = "Disable";
					}
					$emiplan_result[$key]['Status'] = $status_emiplan;
					$emiplan_result[$key]['EmiPlanName'] = $value->EmiPlanName;
					$emiplan_result[$key]['EmiPlanPeriod'] = $value->EmiPlanPeriod;
					$emiplan_result[$key]['EmiPlanOrderMinAmount'] = $value->EmiPlanOrderMinAmount;
					$emiplan_result[$key]['EmiPlanId'] =$value->EmiPlanId;
					
					$key ++;
					
				}
				if(count($emiplan_result)>0){
				$status = 1;
				$msg = "Success";
				$emidata = $emiplan_result;
				}else{
					$status = 0;
					$msg = "Minimum cart amount is not satisfied..";
					$emidata = "";
				}
			}else{
				$status = 0;
				$msg = "There is no EMI Plan available....";
				$emidata = '';
			}

		}else{
			$status = 0;
			$msg = "You don't have sufficient credit balance";
			$emidata = '';
		}
			


		
		$jsondata = array();
		$jsondata['status']=$status;
		$jsondata['msg']=$msg;
		$jsondata['emidata'] = $emidata;

       return $this->asJson($jsondata);
	}
	public function actionUpdateemidata(){
		$post = Yii::$app->request->post();
		extract($post);
		$CartIdentifire = $this->getCartidentifier();
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire,'CartStatus'=>0])->one();
		$cartamount = $Cart->RegularTotalPrice;
		$emidetails = EmiPlans::find()->where(['EmiPlanId'=>$emiplanid])->one();
		$emiplanperiod = $emidetails->EmiPlanPeriod;
		$emiamount = $cartamount / $emiplanperiod;
		
		/*************** Data update *****************/
		$Cart->EmiPlanId = $emiplanid;
		$Cart->PaymentType = $emimethod;
		$Cart->EmiPlanPeriod = $emiplanperiod;
		$Cart->EmiAmount = $emiamount;
		$Cart->CreditBalanceUsed = $cartamount;
		if($Cart->save()){
			$status = 1;
			$msg = "Success";
		}else{
			$status = 0;
			$msg = "Failure in Cart update";
		}	
		
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);	
	}
	public function actionCheckpaymentstaus(){
		
		$EmployeeId = Yii::$app->user->identity->EmployeeId;
		$Employee=Employee::find()->where(['EmployeeId'=>$EmployeeId,'IsDelete'=>0,'EmployeeActiveStatus'=>1])->one();
		$CartIdentifire = $this->getCartidentifier();
		$Cart = Cart::find()->where(['CartIdentifire'=>$CartIdentifire,'CartStatus'=>0])->one();
		$usercreditbalance = $Employee->CreditBalance;
		if($usercreditbalance >= $Cart->RegularTotalPrice){
				$emistatus = 1;
			if($usercreditbalance >= $Cart->DiscountedTotalPrice){
				$directstatus = 1;
			}else{
				$directstatus = 0;
			}
			$status = 0;
			$msg = "You don't have sufficient credit balance";
			$data['emistatus'] = $emistatus ;
			$data['directstatus'] = $directstatus ;
			$data['available_balance'] = $usercreditbalance  ;
		}else{
			$emistatus = 0;
			$directstatus = 0;
			$status = 0;
			$msg = "You don't have sufficient credit balance";
			$data['emistatus'] = $emistatus ;
			$data['directstatus'] = $directstatus ;
			$data['available_balance'] = $usercreditbalance  ;
		}
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		$json_array['result']=$data;
		return $this->asJson($json_array);
		
	}
	
	
}
?>