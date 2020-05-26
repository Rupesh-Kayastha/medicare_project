<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use common\models\TicketOrder;
use common\models\TicketChat;
use common\models\Cart;
use common\models\CartItem;
use common\models\TicketCartMap;
use common\models\EmployeeAddress;
use common\models\EmiPlans;
use backend\models\User;

/**
 * Site controller
 */
class TicketOrderController extends Controller
{
	public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => (Yii::$app->user->identity),
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	public function actionTickethandle()
    {
        $ticketid=Yii::$app->request->get()['ticketid'];
        $ticket=TicketOrder::find()->where(['TicketID'=>$ticketid])->one();
        if($ticket->Operator==0)
        {
        $ticket->Operator=Yii::$app->user->identity->id;
        $ticket->save();
        }
		return $ticket->Token;
    }
    
    public function actionMessagesend()
    {
        $message=Yii::$app->request->get()['message'];
        $TicketOrder=new TicketOrder();
        $TicketChat=new TicketChat();
        
        $TicketToken=Yii::$app->request->get()['token'];
        $Ticket=$TicketOrder::find()->where(['Token'=>$TicketToken])->one();
        $to=$Ticket->UserID;
        $ticketid=$Ticket->TicketID;
        $result=$TicketChat->send(Yii::$app->user->identity->id,$message,$ticketid,$to);
        echo $result;
    }
    
    public function actionFetchmessage()
    {
        $TicketOrder=new TicketOrder();
        $TicketChat=new TicketChat();
        
        $TicketToken=Yii::$app->request->get()['token'];
        $Ticket=$TicketOrder::find()->where(['Token'=>$TicketToken])->one();
        $to=Yii::$app->user->identity->id;
        $ticketid=$Ticket->TicketID;
        
        $lasttime=Yii::$app->request->get()['lasttime'];
        $allmessage=$TicketChat->fetchmessage($ticketid,$to,$lasttime);

		return $this->asJson($allmessage);
		
    }
	
	public function actionTicketemployee(){
		$TicketOrder=new TicketOrder();
		$TicketToken=Yii::$app->request->get()['token'];
		$Ticket=$TicketOrder::find()->where(['Token'=>$TicketToken])->one();
		
		$data['EmpId']=$Ticket->user->EmpId;
		$data['EmployeeName']=$Ticket->user->EmployeeName;
		$data['CompanyName']=$Ticket->user->company->Name;
		$data['CreditLimit']=$Ticket->user->CreditLimit;
		$data['CreditBalance']=$Ticket->user->CreditBalance;
		$data['EmployeeId']=$Ticket->user->EmployeeId;
		
		return $this->asJson($data);
	}
	/******** Product Added to Cart *********/
	public function actionProductadd(){
		$post = Yii::$app->request->post();
		//var_dump($post);
		extract($post);
		
		$TicketCartMapObj = new TicketCartMap();
		$CartObj = new Cart();
		$TicketOrder=TicketOrder::find()->where(['Token'=>$ticket_token])->one();
		
		if($TicketOrder){
			
			$EmployeeId=$TicketOrder->UserID;
			
			$TicketCartMap=$TicketCartMapObj->find()->where(['TicketOrderToken'=>$ticket_token])->one();
			
			if($TicketCartMap){
				$CartIdentifire=$TicketCartMap->CartIdentifire;
				$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
			}
			else{
				
				$count=$CartObj::find()->count()+1;
				$CartIdentifire='AMD_CART'.str_pad($count,9,"0",STR_PAD_LEFT);
				
				$CartObj->CartIdentifire=$CartIdentifire;
				$CartObj->EmployeeId=$EmployeeId;
				//$CartObj->OrderType=$ordertype;
				$CartObj->save();
				$TicketCartMap_obj=new TicketCartMap();
				$TicketCartMap_obj->TicketOrderToken=$ticket_token;
				$TicketCartMap_obj->CartIdentifire = $CartIdentifire;
				$TicketCartMap_obj->save();
				$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
			}
			$Cart->OrderType = $ordertype;
			$Cart->save();
			
			$Cart->addItem($CartIdentifire,$medicineid);			
			
		}
		
		
	}
	public function actionViewcart(){
		$post = Yii::$app->request->post();
		extract($post);
		$payment_deatils = array('0'=>'Not Set','1'=>'COD(Self)','2'=>'Online(Self)','3'=>'Direct-Debit(Company)','4'=>'EMI(Company)');
		$data= array();
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		if($TicketCartMap){
			$CartIdentifire = $TicketCartMap->CartIdentifire;//getCartIdentifire
			$Cart = $TicketCartMap->cartIdentifire;
			$data['CartIdentifire'] = $Cart->CartIdentifire;
			$data['EmployeeId'] = $Cart->EmployeeId;
			$data['RegularTotalPrice'] = $Cart->RegularTotalPrice;
			$data['DiscountedTotalPrice'] = $Cart->DiscountedTotalPrice;
			$data['TicketToken'] = $ticket_token;
			
			$data['PaymentType'] = $payment_deatils[$Cart->PaymentType];
			$CartItems = $Cart->cartItems;
			$items = array();
			$address = array();
			$emi_details = array();
			if($CartItems){
				foreach($CartItems as $item){
					$iremdetails = array();
					$iremdetails['CartItemId'] = $item->CartItemId;
					$iremdetails['CartMedicineId'] = $item->CartMedicineId;
					$iremdetails['CartItemName'] = $item->CartItemName;
					$iremdetails['CartItemQty'] = $item->CartItemQty;
					$iremdetails['CartItemRegularPrice'] = $item->CartItemRegularPrice;
					$iremdetails['CartItemDiscountedPrice'] = $item->CartItemDiscountedPrice;
					$iremdetails['CartItemRegularRowTotal'] = $item->CartItemRegularRowTotal;
					$iremdetails['CartItemDiscountedRowTotal'] = $item->CartItemDiscountedRowTotal;
					$iremdetails['IsPrescription'] = $item->IsPrescription;
					$items[$item->CartItemId] = $iremdetails;
				}
				
			}
			$data['CartItems'] = $items;
			if($Cart->AddressID!=0){
				$employeeaddress = EmployeeAddress::find()->where(['EmployeeAddressId'=>$Cart->AddressID])->one();
				$address['EmployeeAddressId'] = $employeeaddress->EmployeeAddressId;
				$address['AddressLine1'] = $employeeaddress->AddressLine1;
				$address['AddressLine2'] = $employeeaddress->AddressLine2;
				$address['LandMark'] = $employeeaddress->LandMark;
				$address['State'] = $employeeaddress->State;
				$address['City'] = $employeeaddress->City;
				$address['Zipcode'] = $employeeaddress->Zipcode;
				$address['ContactNo'] = $employeeaddress->ContactNo;
			}else{
				$address = "";
			}
			$data['EmployeeAddress'] = $address;
			if($Cart->EmiPlanId!=0){
				$emidetails = EmiPlans::find()->where(['EmiPlanId'=>$Cart->EmiPlanId])->one();
				$emi_details['EmiPlanId'] = $emidetails->EmiPlanId;
				$emi_details['EmiPlanName'] = $emidetails->EmiPlanName;
				$emi_details['EmiPlanPeriod'] = $emidetails->EmiPlanPeriod;
				$emi_details['EmiPlanOrderMinAmount'] = $emidetails->EmiPlanPeriod;
				$emi_details['EmiAmount'] = $Cart->EmiAmount;
			}else{
				$emi_details = "";
			}
			$data['EmiDetails'] = $emi_details;
			if($Cart->PaymentType!=0 && $Cart->AddressID!=0 && ($Cart->RegularTotalPrice>0 || $Cart->DiscountedTotalPrice>0)){
				$generatebtn_status = 1;
			}else{
				$generatebtn_status = 0;
			}
			$data['generatebtn_status'] = $generatebtn_status;
		}
		
		return $this->asJson($data);
	}
	public function actionUpdatequantity(){
		$post = Yii::$app->request->post();
		
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		if($TicketCartMap){
			$CartIdentifire = $TicketCartMap->CartIdentifire;//getCartIdentifire
			
			$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
			$qty = -1;
			$Cart->updateItem($CartIdentifire,$medicineid,$qty);
			
		}
		$checkcart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
		if($checkcart){
			if($checkcart->RegularTotalPrice == 0 && $checkcart->DiscountedTotalPrice == 0 ){
				$status = 1;
			}else{
				$cart_check = CartItem::find()->where(['CartIdentifire'=>$CartIdentifire,'CartMedicineId'=>$medicineid])->one();
				if($cart_check){
					$status = 0;
				}else{
				    $status = 1;
				}
			}
		}else{
			$status = 0;
		}
		/*Check the Cart is Empty*/
		
		return $this->asJson($status);
	}
	public function actionRemoveItem(){
		
		$post = Yii::$app->request->post();
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		if($TicketCartMap){
			$CartIdentifire = $TicketCartMap->CartIdentifire;//getCartIdentifire
			$Cart=Cart::find()->where(['CartIdentifire'=>$CartIdentifire])->one();
			$Cart->removeItem($CartIdentifire,$medicineid);			
			
		}
	}
	public function actionSetpayment(){
		$post = Yii::$app->request->post();
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		if($TicketCartMap){
			$CartIdentifire = $TicketCartMap->CartIdentifire;//getCartIdentifire
			$Cart = $TicketCartMap->cartIdentifire;
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
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		
	}

	public function actionEmipayment(){
		$post = Yii::$app->request->post();
		extract($post);
		$emiplan_result = array();
		
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		$Employee=$TicketCartMap->ticketOrderToken->user;
		$usercreditbalance = $Employee->CreditBalance;

			
			if($TicketCartMap){
				$CartIdentifire = $TicketCartMap->CartIdentifire;
				$Cart = $TicketCartMap->cartIdentifire;
				$CartItemRegularPrice = $Cart->RegularTotalPrice;
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
						


						//var_dump($companyemi);
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
			}else{
				$status = 0;
				$msg = "There is some error in ticket-cart-map....";
				$emidata = '';
			}


		
		$jsondata = array();
		$jsondata['status']=$status;
		$jsondata['msg']=$msg;
		$jsondata['emidata'] = $emidata;

       return $this->asJson($jsondata);
	}

	public function actionGetuseraddress(){
		$post = Yii::$app->request->post();
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		$EmployeeAddress=$TicketCartMap->ticketOrderToken->user->employeeAddresses;
		if($EmployeeAddress){
			$address = array();
			foreach($EmployeeAddress as $addresskey=>$addressvalue){
				$address[$addresskey]['EmployeeAddressId'] = $addressvalue->EmployeeAddressId;
				$address[$addresskey]['AddressLine1'] = $addressvalue->AddressLine1;
				$address[$addresskey]['AddressLine2'] = $addressvalue->AddressLine2;
				$address[$addresskey]['LandMark'] = $addressvalue->LandMark;
				$address[$addresskey]['State'] = $addressvalue->State;
				$address[$addresskey]['City'] = $addressvalue->City;
				$address[$addresskey]['Zipcode'] = $addressvalue->Zipcode;
				$address[$addresskey]['ContactNo'] = $addressvalue->ContactNo;
			}
			$status = 1;
			$msg = "Success";
			$data = $address;
		}else{
			$status = 0;
			$msg = "No address available.Please insert the address...";
			$data = "";
		}
		$jsondata = array();
		$jsondata['status']=$status;
		$jsondata['msg']=$msg;
		$jsondata['addressdata'] = $data;
		return $this->asJson($jsondata);
		
	}
	public function actionCreateemployeeaddress(){
		$post = Yii::$app->request->post();
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		$EmployeeDetails=$TicketCartMap->ticketOrderToken->user;
		$employeeid = $EmployeeDetails->EmployeeId;
		$EmployeeAddress = new EmployeeAddress();
		$EmployeeAddress->EmployeeId = $employeeid;
		$EmployeeAddress->AddressLine1 = $addressline1;
		$EmployeeAddress->AddressLine2 = $addressline2;
		$EmployeeAddress->LandMark = $landmark;
		$EmployeeAddress->State = $state;
		$EmployeeAddress->City = $city;
		$EmployeeAddress->Zipcode = $zipcode;
		$EmployeeAddress->ContactNo = $contactno;
		if($EmployeeAddress->save()){
			$status = 1;
			$msg = "Employee address create successfully";
		}else{
			$status = 0;
			$msg = "Employee address not created..";
		}
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);
	}
	public function actionSetemployeeaddress(){
		$post = Yii::$app->request->post();
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		if($TicketCartMap){
			$CartIdentifire = $TicketCartMap->CartIdentifire;
			$Cart = $TicketCartMap->cartIdentifire;
			if($Cart){
				$Cart->AddressID = $addressid;
				if($Cart->save()){
					$status = 1;
					$msg = "Address update successfully";
				}else{
					$status = 0;
					$msg = "There is some error please try after sometimes...";
				}
			}else{
				$status = 0;
				$msg = "Cart Record not available..";
			}
		}else{
			$status = 0;
			$msg = "TicketCartMap Record not available..";
		}
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);		
	}
	public function actionUpdateemidata(){
		$post = Yii::$app->request->post();
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		if($TicketCartMap){
			$CartIdentifire = $TicketCartMap->CartIdentifire;
			$Cart = $TicketCartMap->cartIdentifire;
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
		}else{
			$status = 0;
			$msg = "Failure in TicketCartMap data get..";
		}
		$json_array = array();
		$json_array['status']=$status;
		$json_array['msg']=$msg;
		return $this->asJson($json_array);	
	}
	
	
	public function actionGetalltoken(){
		$all_ticket=array();
		$html_data = "";
        if(Yii::$app->user->identity->SystemRoleId==User::SYSTEM_USER_OPERATOR)
        {
			$allticket=TicketOrder::find()->where(['TicketStatus'=>1])->andWhere(['IN','Operator',[0,Yii::$app->user->identity->id]])->orderBy(['OnDate'=>SORT_DESC])->all();
			if($allticket){
				$status = 1;
				foreach($allticket as $key=>$val){
					$TicketID = $val->TicketID;
					$Token = (string)$val->Token;
					$html_data .= "<div  class='col-md-12 col-xs-12 ticket-box pointer' onclick='tickethandle($TicketID,&#39;$Token&#39;);'>";
					$html_data .= "Ticket - ".$val->Token."<br/>";
					$html_data .= "Employee Name - ".$val->user->EmployeeName."<br/>";
					$html_data .= "Time - ".date('d M Y H:i:s',strtotime($val->OnDate))."<br/>";
					$html_data .= "</div>";
				}
				$data = trim($html_data);
			}else{
				$status = 0;
				$data = "";
			}
        }
		$json_result = array();
		$json_result['status']=$status;
		$json_result['data']=$data;
		return $this->asJson($json_result);
		
	}
	
	public function actionLinkgenerate(){
		$post = Yii::$app->request->post();
		extract($post);
		$TicketCartMap = TicketCartMap::find()->where(['TicketOrderToken'=>$ticket_token])->one();
		if($TicketCartMap){
			$status = 1;
			$CartIdentifire = $TicketCartMap->CartIdentifire;
			$token = $ticket_token;
			$data['CartIdentifire'] = $CartIdentifire;
			$data['token'] = $token;
			$basefronturl = Yii::getAlias('@frontendUrl');
			$url = $basefronturl."/site/viewoperatorcart?crtid=".$CartIdentifire;
			//$data['link'] = "<a href='".$url."' target='_blank'>ViewCart</a>";
			$data['link'] = '<a onclick="cartReview(\''.$CartIdentifire.'\')" style="cursor:pointer;">Review Order</a>';
			
			//$data['link'] = "<a href=''"
		}else{
			$status = 0;
			$data = "";
		}
		
		//echo Yii::getAlias('@frontendUrl');
		
		$json_result = array();
		$json_result['status'] = $status;
		$json_result['data'] = $data;
		return $this->asJson($json_result);
	}
	
	
}

?>