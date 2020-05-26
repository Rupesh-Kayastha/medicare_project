<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Orders;

class OrderController extends BaseController
{
 
   public function actionOrderdetail()
   {
      $results=array();
      $data=array();
      
      $emparr=array();
      $empaddrarr=array();
      $orderitemarr=array();
      
      $orderid=Yii::$app->request->post('orderid');
      $value=Orders::find()->where(['OrderId'=>$orderid,'IsDelete'=>0])->one();
      if(count($value)>0)
      {
            
              $data['CartIdentifire']=$value->CartIdentifire;
              $data['OrderTotalPrice']=$value->OrderTotalPrice;
              if($value->PaymentType==1)
              {
                $paymenttype='COD';
				$data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="N/A";
                $data['EmiAmount']="N/A";
                $data['CreditBalanceUsed']="N/A";
              }
              else if($value->PaymentType==2)
              {
                $paymenttype='Online';
				$data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="N/A";
                $data['EmiAmount']="N/A";
                $data['CreditBalanceUsed']="N/A";
              }
              else if($value->PaymentType==3)
              {
                $paymenttype='Direct';
                $data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="One Time";
                $data['EmiAmount']="&#x20B9; ".$value->EmiAmount;
                $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              }
              else if($value->PaymentType==4)
              {
                $paymenttype='EMI';
                $data['EmiPlanId']=$value->EmiPlanId;
                $data['EmiPlanPeriod']=$value->EmiPlanPeriod." Months";
                $data['EmiAmount']="&#x20B9; ".$value->EmiAmount;
                $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              }
              $data['PaymentType']=$paymenttype;
              
              if($value->OrderType==0)
              {
                $ordertype='Not Set';
              }
              else if($value->OrderType==1)
              {
                $ordertype='Order By Support';
              }
              else if($value->OrderType==2)
              {
                $ordertype='Order By Self';
              }
              
              if($value->OrderStatus==0)
              {
                $orderstatus='Open';
              }
              else if($value->OrderStatus==1)
              {
                $orderstatus='Confirmed';
              }
              else if($value->OrderStatus==2)
              {
                $orderstatus='Rejected';
              }
              $data['OrderType']=$ordertype;
              $data['OrderStatus']=$orderstatus;
              $data['CreatedDate']=$value->CreatedDate;
              $data['EmployeeId']=$value->EmployeeId;
              if(isset($value->employee))
              {
                  $emparr['EmpId']=$value->employee->EmpId;
                  $emparr['CompanyId']=$value->employee->CompanyId;
                  $emparr['CompanyName']=$value->employee->company->Name;
                  $emparr['EmployeeName']=$value->employee->EmployeeName;
                  $emparr['ContactNo']=$value->employee->ContactNo;
                  $emparr['EmailId']=$value->employee->EmailId;
                  $data['employeedetails']=$emparr;
              }
              
              $data['DeliveryAddressID']=$value->DeliveryAddressID;
              if(isset($value->employeeaddress))
              {
                $empaddrarr['AddressLine1']=$value->employeeaddress->AddressLine1;
                $empaddrarr['AddressLine2']=$value->employeeaddress->AddressLine2;
                $empaddrarr['LandMark']=$value->employeeaddress->LandMark;
                $empaddrarr['State']=$value->employeeaddress->State;
                $empaddrarr['City']=$value->employeeaddress->City;
                $empaddrarr['Zipcode']=$value->employeeaddress->Zipcode;
                $empaddrarr['ContactNo']=$value->employeeaddress->ContactNo;
                $data['addressdetails']=$empaddrarr;
              }
              
              if(isset($value->orderitems))
              {
                
                foreach($value->orderitems as $k1=>$v1)
                {
                    $orderitemarr['OrderMedicineID']=$v1->OrderMedicineID;
                    $orderitemarr['OrderItemName']=$v1->OrderItemName;
                    $orderitemarr['OrderItemQty']=$v1->OrderItemQty;
                    $orderitemarr['OrderItemPrice']=$v1->OrderItemPrice;
                    $orderitemarr['OrderItemTotalPrice']=$v1->OrderItemTotalPrice;
                    $orderitemarr['IsPrescription']=$v1->IsPrescription?"Yes":"No";
                    $data['orderitemdetails'][]=$orderitemarr;
                }
                
                
              }
            
            $status=1;
            $msg='Record Found.';
      }
      else
      {
            $status=0;
            $msg='No Record Found.';
      }
      
        $results['status']=$status;
        $results['message']=$msg;
        $results['orderdetail']=$data;
        
        $this->sendResponce($results);
      
   }
    public function actionAllorder()
    {
        $results=array();
        $arr=array();
        $emparr=array();
        
        $employeeid=Yii::$app->request->post('Employeeid');
        $res=Orders::find()->where(['EmployeeId'=>$employeeid,'IsDelete'=>0])->orderBy(['OrderId'=>SORT_DESC])->all();
        if(count($res)>0)
        {
             
            foreach($res as $key=>$value)
            {
			  $data=[];
              $data['OrderId']=$value->OrderId;  
              $data['OrderIdentifier']=$value->OrderIdentifier;
              $data['OrderTotalPrice']=$value->OrderTotalPrice;
			  $data['EmiPlanId']=$value->EmiPlanId;
              $data['EmiPlanPeriod']=$value->EmiPlanPeriod;
              $data['EmiAmount']=$value->EmiAmount;
              $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              if($value->PaymentType==1)
              {
                $paymenttype='COD';
				$data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="N/A";
                $data['EmiAmount']="N/A";
                $data['CreditBalanceUsed']="N/A";
              }
              else if($value->PaymentType==2)
              {
                $paymenttype='Online';
				$data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="N/A";
                $data['EmiAmount']="N/A";
                $data['CreditBalanceUsed']="N/A";
              }
              else if($value->PaymentType==3)
              {
                $paymenttype='Direct';
                $data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="One Time";
                $data['EmiAmount']="&#x20B9; ".$value->EmiAmount;
                $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              }
              else if($value->PaymentType==4)
              {
                $paymenttype='EMI';
                $data['EmiPlanId']=$value->EmiPlanId;
                $data['EmiPlanPeriod']=$value->EmiPlanPeriod." Months";
                $data['EmiAmount']="&#x20B9; ".$value->EmiAmount;
                $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              }
              $data['PaymentType']=$paymenttype;
              
              if($value->OrderType==0)
              {
                $ordertype='Not Set';
              }
              else if($value->OrderType==1)
              {
                $ordertype='Order By Support';
              }
              else if($value->OrderType==2)
              {
                $ordertype='Order By Self';
              }
              
              if($value->OrderStatus==0)
              {
                $orderstatus='Open';
              }
              else if($value->OrderStatus==1)
              {
                $orderstatus='Confirmed';
              }
              else if($value->OrderStatus==2)
              {
                $orderstatus='Rejected';
              }
              $data['OrderType']=$ordertype;
              $data['OrderStatus']=$orderstatus;
              $data['CreatedDate']=$value->CreatedDate;
              
              if(isset($value->orderitems))
              {
                
                $arr1=array();
                foreach($value->orderitems as $k1=>$v1)
                {
					$orderitemarr=[];
                    $orderitemarr['OrderMedicineID']=$v1->OrderMedicineID;
                    $orderitemarr['OrderItemName']=$v1->OrderItemName;
                    $orderitemarr['OrderItemQty']=$v1->OrderItemQty;
                    $orderitemarr['OrderItemPrice']=$v1->OrderItemPrice;
                    $orderitemarr['OrderItemTotalPrice']=$v1->OrderItemTotalPrice;
                    $orderitemarr['IsPrescription']=$v1->IsPrescription;
                    
                    array_push($arr1,$orderitemarr);
                }
                
                $data['items']=$arr1;
              }
                array_push($arr,$data);
            
                $status=1;
                $msg='Record Found.';
            }
        }
        else
        {
              $status=0;
              $msg='No Record Found.';
        }
      
        $results['status']=$status;
        $results['message']=$msg;
        $results['orders']=$arr;
        
        $this->sendResponce($results);
    }
	public function actionAllsubscriptions()
    {
        $results=array();
        $arr=array();
        $emparr=array();
        
        $employeeid=Yii::$app->request->post('Employeeid');
        $res=Orders::find()->where(['EmployeeId'=>$employeeid,'IsDelete'=>0,'MonthlySubscription'=>1])->andWhere(new \yii\db\Expression('`ParentOrderIdentifier`=`OrderIdentifier`'))->orderBy(['OrderId'=>SORT_DESC])->all();
        if(count($res)>0)
        {
             
            foreach($res as $key=>$value)
            {
			  $data=[];
              $data['OrderId']=$value->OrderId;  
              $data['OrderIdentifier']=$value->OrderIdentifier;
              $data['OrderTotalPrice']=$value->OrderTotalPrice;
			  $data['EmiPlanId']=$value->EmiPlanId;
              $data['EmiPlanPeriod']=$value->EmiPlanPeriod;
              $data['EmiAmount']=$value->EmiAmount;
              $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              if($value->PaymentType==1)
              {
                $paymenttype='COD';
				$data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="N/A";
                $data['EmiAmount']="N/A";
                $data['CreditBalanceUsed']="N/A";
              }
              else if($value->PaymentType==2)
              {
                $paymenttype='Online';
				$data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="N/A";
                $data['EmiAmount']="N/A";
                $data['CreditBalanceUsed']="N/A";
              }
              else if($value->PaymentType==3)
              {
                $paymenttype='Direct';
                $data['EmiPlanId']="N/A";
                $data['EmiPlanPeriod']="One Time";
                $data['EmiAmount']="&#x20B9; ".$value->EmiAmount;
                $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              }
              else if($value->PaymentType==4)
              {
                $paymenttype='EMI';
                $data['EmiPlanId']=$value->EmiPlanId;
                $data['EmiPlanPeriod']=$value->EmiPlanPeriod." Months";
                $data['EmiAmount']="&#x20B9; ".$value->EmiAmount;
                $data['CreditBalanceUsed']=$value->CreditBalanceUsed;
              }
			  $data['PaymentType']=$paymenttype;
			  
			  if($value->SubscriptionStatus==1){
				 $data['SubscriptionStatus']="Active"; 
			  }
			  else{
				   $data['SubscriptionStatus']="Inactive";
			  }
              
              
              if($value->OrderType==0)
              {
                $ordertype='Not Set';
              }
              else if($value->OrderType==1)
              {
                $ordertype='Order By Support';
              }
              else if($value->OrderType==2)
              {
                $ordertype='Order By Self';
              }
              
              if($value->OrderStatus==0)
              {
                $orderstatus='Open';
              }
              else if($value->OrderStatus==1)
              {
                $orderstatus='Confirmed';
              }
              else if($value->OrderStatus==2)
              {
                $orderstatus='Rejected';
              }
              $data['OrderType']=$ordertype;
              $data['OrderStatus']=$orderstatus;
              $data['CreatedDate']=$value->CreatedDate;
              
              if(isset($value->orderitems))
              {
                
                $arr1=array();
                foreach($value->orderitems as $k1=>$v1)
                {
					$orderitemarr=[];
                    $orderitemarr['OrderMedicineID']=$v1->OrderMedicineID;
                    $orderitemarr['OrderItemName']=$v1->OrderItemName;
                    $orderitemarr['OrderItemQty']=$v1->OrderItemQty;
                    $orderitemarr['OrderItemPrice']=$v1->OrderItemPrice;
                    $orderitemarr['OrderItemTotalPrice']=$v1->OrderItemTotalPrice;
                    $orderitemarr['IsPrescription']=$v1->IsPrescription;
                    
                    array_push($arr1,$orderitemarr);
                }
                
                $data['items']=$arr1;
              }
			  
			  
                array_push($arr,$data);
            
                $status=1;
                $msg='Record Found.';
            }
        }
        else
        {
              $status=0;
              $msg='No Record Found.';
        }
      
        $results['status']=$status;
        $results['message']=$msg;
        $results['orders']=$arr;
        
        $this->sendResponce($results);
    }
   
}
?>